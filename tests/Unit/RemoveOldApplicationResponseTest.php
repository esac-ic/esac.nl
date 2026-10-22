<?php

namespace Tests\Unit;

use App\Enums\Role;
use App\Exports\AgendaRegistrationExport;
use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\ApplicationForm\ApplicationResponseRow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RemoveOldApplicationResponseTest extends TestCase
{
    use RefreshDatabase;

    private function registrationForEventThatEnded(Carbon $endDate): ApplicationResponse
    {
        $agendaItem = AgendaItem::factory()->create(['end_date' => $endDate]);
        $applicationResponse = ApplicationResponse::factory()->create(['agenda_id' => $agendaItem->id]);
        foreach (ApplicationFormRow::factory()->count(2)->create(['application_form_id' => $applicationResponse->inschrijf_form_id]) as $row) {
            $applicationResponse->rows()->create(['application_form_row_id' => $row->id, 'value' => 'answer']);
        }

        return $applicationResponse;
    }

    #[Test]
    public function the_registrations_of_an_event_that_ended_three_years_ago_are_anonymised()
    {
        $applicationResponse = $this->registrationForEventThatEnded(now()->subYears(3));

        $this->artisan('remove:oldApplications');

        $applicationResponse->refresh();
        $this->assertNull($applicationResponse->user_id);
        $this->assertSame(0, ApplicationResponseRow::withTrashed()->count());
        $this->assertSame(1, $applicationResponse->agendaItem->registrations()->count());

        // Counted on the event page, but no longer listed there, on the admin page or in the export
        $agendaItem = $applicationResponse->agendaItem;
        $this->actingAs(User::factory()->withRole(Role::ActivityAdministrator)->create());
        $this->get(route('front-end.agenda.detail', $agendaItem))
            ->assertOk()
            ->assertViewHas('registrations', fn ($registrations) => $registrations->isEmpty())
            ->assertViewHas('agendaItem', fn ($agendaItem) => $agendaItem->registrations_count === 1);
        $this->get(route('beheer.agenda-items.registrations.index', $agendaItem))
            ->assertOk()
            ->assertViewHas('registrations', fn ($registrations) => $registrations->isEmpty());
        $this->assertCount(0, new AgendaRegistrationExport($agendaItem)->collection());
    }

    #[Test]
    public function the_registrations_of_an_event_that_ended_a_year_ago_are_kept()
    {
        $applicationResponse = $this->registrationForEventThatEnded(now()->subYear());
        $applicationResponse->forceFill(['created_at' => now()->subYears(3)])->save();

        $this->artisan('remove:oldApplications');

        $this->assertNotNull($applicationResponse->fresh()->user_id);
        $this->assertSame(2, ApplicationResponseRow::count());
    }

    #[Test]
    public function a_cancelled_registration_of_an_event_that_ended_three_years_ago_is_deleted()
    {
        $applicationResponse = $this->registrationForEventThatEnded(now()->subYears(3));
        $applicationResponse->delete();

        $this->artisan('remove:oldApplications');

        $this->assertSame(0, ApplicationResponse::withTrashed()->count());
        $this->assertSame(0, ApplicationResponseRow::withTrashed()->count());
    }
}
