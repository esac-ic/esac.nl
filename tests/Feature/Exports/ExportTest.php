<?php

namespace Tests\Feature\Exports;

use App\Enums\AdminRole;
use App\Enums\FormFieldType;
use App\Models\Book;
use App\Models\Event;
use App\Models\MemberLogEntry;
use App\Models\Registration;
use App\Models\RegistrationForm;
use App\Models\RegistrationFormField;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use OpenSpout\Reader\XLSX\Reader;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use ZipArchive;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->withRole(AdminRole::Administrator, AdminRole::EventAdministrator)->create();
        $this->actingAs($this->admin);
    }

    #[Test]
    public function members_export_lists_the_active_members()
    {
        User::factory()->count(2)->create();
        User::factory()->former()->create();

        [$sheet, $rows] = $this->readSpreadsheet($this->get('admin/members/export'), 'Members.xlsx');

        $this->assertSame('Active members', $sheet);
        $this->assertSame('#', $rows[0][0]);
        $this->assertSame('Certificates', $rows[0][25]);
        $this->assertCount(26, $rows[0]);
        $this->assertCount(26, $rows[1]);
        $this->assertCount(1 + 3, $rows);
    }

    #[Test]
    public function former_members_export_lists_the_former_members()
    {
        User::factory()->count(2)->former()->create();

        [$sheet, $rows] = $this->readSpreadsheet($this->get('admin/former-members/export'), 'Former members.xlsx');

        $this->assertSame('Former members', $sheet);
        $this->assertSame(['#', 'First name', 'Preposition', 'Last name', 'Signed up on', 'Membership ended on'], $rows[0]);
        $this->assertCount(1 + 2, $rows);
    }

    #[Test]
    public function penno_export_lists_the_members_created_after_the_given_date()
    {
        User::factory()->create(['created_at' => now()->subDays(10)]);
        User::factory()->create(['created_at' => now()->subDay()]);

        $response = $this->post('admin/penno-export', ['days_ago' => 5]);
        $exportDate = today()->subDays(5)->toDateString();

        [$sheet, $rows] = $this->readSpreadsheet($response, "Members (created after $exportDate).xlsx");

        $this->assertSame('New members', $sheet);
        $this->assertCount(12, $rows[0]);
        // The admin and the member created yesterday
        $this->assertCount(1 + 2, $rows);
    }

    #[Test]
    public function books_export_lists_the_books()
    {
        Book::create(['title' => 'Alpine climbing', 'year' => 1990, 'country' => 'CH', 'type' => 'Guide', 'code' => 'A1']);

        [$sheet, $rows] = $this->readSpreadsheet($this->get('admin/books/export'), 'Books.xlsx');

        $this->assertSame('Books', $sheet);
        $this->assertSame(['#', 'Title', 'Year', 'Type', 'Country', 'Code'], $rows[0]);
        $this->assertSame('Alpine climbing', $rows[1][1]);
        $this->assertCount(1 + 1, $rows);
    }

    #[Test]
    public function text_that_looks_like_a_formula_is_exported_as_text()
    {
        Book::create(['title' => '=HYPERLINK("https://example.org")', 'year' => 1990, 'country' => 'CH', 'type' => 'Guide', 'code' => 'A1']);
        $path = tempnam(sys_get_temp_dir(), 'export');
        file_put_contents($path, $this->get('admin/books/export')->streamedContent());

        // OpenSpout's reader turns any text that starts with "=" into a formula, so the sheet's XML shows what was written.
        $zip = new ZipArchive;
        $zip->open($path);
        $sheet = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();
        unlink($path);

        $this->assertStringContainsString('<t>=HYPERLINK(&quot;https://example.org&quot;)</t>', $sheet);
        $this->assertStringNotContainsString('<f>', $sheet);
    }

    #[Test]
    public function member_log_export_lists_the_entries_in_the_period()
    {
        MemberLogEntry::factory()->count(3)->create(['user_id' => $this->admin->id, 'created_at' => now()]);
        MemberLogEntry::factory()->create(['user_id' => $this->admin->id, 'created_at' => now()->subWeek()]);

        $response = $this->post('admin/member-log/export', [
            'start_date' => now()->subDay()->toDateTimeLocalString('minute'),
            'end_date' => now()->addDay()->toDateTimeLocalString('minute'),
        ]);

        [$sheet, $rows] = $this->readSpreadsheet($response);

        $this->assertStringStartsWith('Member log (', $sheet);
        $this->assertSame(['Time', 'Type', 'Member', 'Details'], $rows[0]);
        $this->assertSame($this->admin->name, $rows[1][2]);
        $this->assertCount(1 + 3, $rows);
    }

    #[Test]
    public function registrations_export_lists_the_registered_members_and_their_answers()
    {
        $form = RegistrationForm::factory()->create();
        RegistrationFormField::factory()->create([
            'registration_form_id' => $form->id,
            'name' => 'Diet',
            'type' => FormFieldType::Text,
        ]);
        $event = Event::factory()->create(['title' => 'Trip to Font', 'registration_form_id' => $form->id]);
        Registration::factory()->count(2)->create([
            'event_id' => $event->id,
            'registration_form_id' => $form->id,
        ]);

        $response = $this->get("admin/events/{$event->id}/registrations/export");

        [$sheet, $rows] = $this->readSpreadsheet($response, 'trip-to-font.xlsx');

        $this->assertSame('Registrations', $sheet);
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
