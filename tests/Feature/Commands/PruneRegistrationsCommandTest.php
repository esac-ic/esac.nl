<?php

namespace Tests\Feature\Commands;

use App\Enums\AdminRole;
use App\Exports\EventRegistrationsExport;
use App\Models\Event;
use App\Models\Registration;
use App\Models\RegistrationAnswer;
use App\Models\RegistrationFormField;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PruneRegistrationsCommandTest extends TestCase
{
    use RefreshDatabase;

    private function registrationForEventThatEnded(Carbon $endDate): Registration
    {
        $registration = Registration::factory()->create(['event_id' => Event::factory()->create(['end_date' => $endDate])]);
        foreach (RegistrationFormField::factory()->count(2)->create(['registration_form_id' => $registration->registration_form_id]) as $field) {
            $registration->answers()->create(['registration_form_field_id' => $field->id, 'value' => 'answer']);
        }

        return $registration;
    }

    #[Test]
    public function the_registrations_of_an_event_that_ended_three_years_ago_are_anonymised()
    {
        $registration = $this->registrationForEventThatEnded(now()->subYears(3));

        $this->artisan('registrations:prune');

        $registration->refresh();
        $this->assertNull($registration->user_id);
        $this->assertSame(0, RegistrationAnswer::query()->count());
        $this->assertSame(1, $registration->event->registrations()->count());

        // Counted on the event page, but no longer listed there, on the admin page or in the export
        $event = $registration->event;
        $this->actingAs(User::factory()->withRole(AdminRole::EventAdministrator)->create());
        $this->get(route('events.show', $event))
            ->assertOk()
            ->assertViewHas('registrations', fn ($registrations) => $registrations->isEmpty())
            ->assertViewHas('event', fn ($event) => $event->registrations_count === 1);
        $this->get(route('admin.events.registrations.index', $event))
            ->assertOk()
            ->assertViewHas('registrations', fn ($registrations) => $registrations->isEmpty());
        $this->assertCount(0, new EventRegistrationsExport($event)->collection());
    }

    #[Test]
    public function the_registrations_of_an_event_that_ended_a_year_ago_are_kept()
    {
        $registration = $this->registrationForEventThatEnded(now()->subYear());
        $registration->forceFill(['created_at' => now()->subYears(3)])->save();

        $this->artisan('registrations:prune');

        $this->assertNotNull($registration->fresh()->user_id);
        $this->assertSame(2, RegistrationAnswer::count());
    }

    #[Test]
    public function a_cancelled_registration_of_an_event_that_ended_three_years_ago_is_deleted()
    {
        $registration = $this->registrationForEventThatEnded(now()->subYears(3));
        $registration->delete();

        $this->artisan('registrations:prune');

        $this->assertSame(0, Registration::query()->count());
        $this->assertSame(0, RegistrationAnswer::query()->count());
    }
}
