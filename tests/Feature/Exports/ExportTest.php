<?php

namespace Tests\Feature\Exports;

use App\Enums\Role;
use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\Book;
use App\Models\User;
use App\Models\UserEventLogEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use OpenSpout\Reader\XLSX\Reader;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->withRole(Role::Administrator, Role::ActivityAdministrator)->create();
        $this->actingAs($this->admin);
    }

    #[Test]
    public function members_export_lists_the_active_members()
    {
        User::factory()->count(2)->create();
        User::factory()->lidAf()->create();

        [$sheet, $rows] = $this->readSpreadsheet($this->get('beheer/users/export'), 'Members.xlsx');

        $this->assertSame('Active members', $sheet);
        $this->assertSame('#', $rows[0][0]);
        $this->assertSame('Certificates', $rows[0][25]);
        $this->assertCount(26, $rows[0]);
        $this->assertCount(26, $rows[1]);
        $this->assertCount(1 + 3, $rows);
    }

    #[Test]
    public function old_members_export_lists_the_old_members()
    {
        User::factory()->count(2)->lidAf()->create();

        [$sheet, $rows] = $this->readSpreadsheet($this->get('beheer/users/old-members/export'), 'Old members.xlsx');

        $this->assertSame('Old members', $sheet);
        $this->assertSame(['#', 'First name', 'Preposition', 'Last name', 'User created at', 'User removed at'], $rows[0]);
        $this->assertCount(1 + 2, $rows);
    }

    #[Test]
    public function penno_export_lists_the_members_created_after_the_given_date()
    {
        User::factory()->create(['created_at' => now()->subDays(10)]);
        User::factory()->create(['created_at' => now()->subDay()]);

        $response = $this->get('beheer/users/penno-export/download?days_ago=5');
        $exportDate = today()->subDays(5)->toDateString();

        [$sheet, $rows] = $this->readSpreadsheet($response, "Members (created after $exportDate).xlsx");

        $this->assertSame('Non administrated members', $sheet);
        $this->assertCount(12, $rows[0]);
        // The admin and the member created yesterday
        $this->assertCount(1 + 2, $rows);
    }

    #[Test]
    public function library_export_lists_the_books()
    {
        Book::create(['title' => 'Alpine climbing', 'year' => 1990, 'country' => 'CH', 'type' => 'Guide', 'code' => 'A1']);

        [$sheet, $rows] = $this->readSpreadsheet($this->get('beheer/books/export'), 'Library.xlsx');

        $this->assertSame('Books', $sheet);
        $this->assertSame(['#', 'Title', 'Year', 'Type', 'Country', 'Code'], $rows[0]);
        $this->assertSame('Alpine climbing', $rows[1][1]);
        $this->assertCount(1 + 1, $rows);
    }

    #[Test]
    public function event_log_export_lists_the_entries_in_the_period()
    {
        UserEventLogEntry::factory()->count(3)->create(['user_id' => $this->admin->id, 'created_at' => now()]);
        UserEventLogEntry::factory()->create(['user_id' => $this->admin->id, 'created_at' => now()->subWeek()]);

        $response = $this->post('beheer/user-event-log/export', [
            'startDate' => now()->subDay()->format('d-m-Y H:i'),
            'endDate' => now()->addDay()->format('d-m-Y H:i'),
        ]);

        [$sheet, $rows] = $this->readSpreadsheet($response);

        $this->assertStringStartsWith('Event Log (', $sheet);
        $this->assertSame(['Time', 'Event Type', 'User name', 'Event Details'], $rows[0]);
        $this->assertSame($this->admin->getName(), $rows[1][2]);
        $this->assertCount(1 + 3, $rows);
    }

    #[Test]
    public function registrations_export_lists_the_registered_users_and_their_answers()
    {
        $form = ApplicationForm::factory()->create();
        ApplicationFormRow::factory()->create([
            'application_form_id' => $form->id,
            'name' => 'Diet',
            'type' => ApplicationFormRow::FORM_TYPE_TEXT,
        ]);
        $agendaItem = AgendaItem::factory()->create(['title' => 'Trip to Font', 'application_form_id' => $form->id]);
        ApplicationResponse::factory()->count(2)->create([
            'agenda_id' => $agendaItem->id,
            'inschrijf_form_id' => $form->id,
        ]);

        $response = $this->get("beheer/agenda-items/{$agendaItem->id}/registrations/export");

        [$sheet, $rows] = $this->readSpreadsheet($response, 'Trip-to-Font.xlsx');

        $this->assertSame('Subscriptions', $sheet);
        $this->assertSame('First name', $rows[0][0]);
        $this->assertSame('Diet', $rows[0][10]);
        $this->assertCount(1 + 2, $rows);
    }

    private function readSpreadsheet(TestResponse $response, ?string $fileName = null): array
    {
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if ($fileName !== null) {
            $response->assertDownload($fileName);
        }

        $path = tempnam(sys_get_temp_dir(), 'export');
        file_put_contents($path, $response->streamedContent());

        $reader = new Reader;
        $reader->open($path);
        $sheets = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            $rows = [];
            foreach ($sheet->getRowIterator() as $row) {
                $rows[] = $row->toArray();
            }
            $sheets[$sheet->getName()] = $rows;
        }
        $reader->close();
        unlink($path);

        $this->assertCount(1, $sheets);

        return [array_key_first($sheets), reset($sheets)];
    }
}
