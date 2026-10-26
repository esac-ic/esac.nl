<?php

namespace Tests\Feature\RegistrationForms;

use App\Enums\AdminRole;
use App\Models\Event;
use App\Models\RegistrationForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\ManagesRegistrationForms;
use Tests\TestCase;

class DeleteRegistrationFormTest extends TestCase
{
    use ManagesRegistrationForms;
    use RefreshDatabase;

    #[Test]
    public function an_event_administrator_can_delete_a_registration_form(): void
    {
        $registrationForm = RegistrationForm::factory()->create();
        $this->actingAsRole(AdminRole::EventAdministrator);

        $this->delete('admin/registration-forms/'.$registrationForm->id)->assertRedirect();

        $this->assertSoftDeleted($registrationForm);
    }

    #[Test]
    public function a_deleted_registration_form_stays_on_past_events_only(): void
    {
        $registrationForm = RegistrationForm::factory()->create();
        $past = Event::factory()->for($registrationForm)->create(['start_date' => now()->subWeek(), 'end_date' => now()->subWeek()]);
        $upcoming = Event::factory()->for($registrationForm)->create(['start_date' => now()->addWeek(), 'end_date' => now()->addWeek()]);
        $this->actingAsRole(AdminRole::EventAdministrator);

        $this->delete('admin/registration-forms/'.$registrationForm->id)->assertRedirect();

        $this->assertTrue($past->fresh()->registrationForm->is($registrationForm));
        $this->assertNull($upcoming->fresh()->registrationForm);
    }
}
