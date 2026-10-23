<?php

namespace Tests\Feature\Commands;

use App\Models\Event;
use App\Models\Registration;
use App\Models\RegistrationAnswer;
use App\Models\RegistrationForm;
use App\Models\RegistrationFormField;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PruneRegistrationsCommandTest extends TestCase
{
    use RefreshDatabase;

    private Registration $registration;

    protected function setUp(): void
    {
        parent::setUp();

        $event = Event::factory()->create(['registration_form_id' => RegistrationForm::factory()]);
        $row = RegistrationFormField::factory()->create(['registration_form_id' => $event->registration_form_id]);
        $event->register(User::factory()->create()->id, [$row->id => 'answer']);
        $this->applicationResponse = Registration::firstOrFail();
    }

    #[Test]
    public function registrations_from_three_years_ago_are_removed()
    {
        $this->applicationResponse->forceFill(['created_at' => now()->subYears(3)])->save();

        $this->artisan('registrations:prune');

        $this->assertSame(0, Registration::withTrashed()->count());
        $this->assertSame(0, RegistrationAnswer::withTrashed()->count());
    }

    #[Test]
    public function registrations_from_a_year_ago_are_kept()
    {
        $this->applicationResponse->forceFill(['created_at' => now()->subYear()])->save();

        $this->artisan('registrations:prune');

        $this->assertSame(1, Registration::count());
        $this->assertSame(1, RegistrationAnswer::count());
    }
}
