<?php

namespace Tests\Feature\RegistrationForms;

use App\Enums\AdminRole;
use App\Exports\EventRegistrationsExport;
use App\Models\Event;
use App\Models\RegistrationForm;
use App\Models\RegistrationFormField;
use App\Models\User;
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
    public function a_form_that_upcoming_events_use_is_not_removed(): void
    {
        $registrationForm = RegistrationForm::factory()->create();
        Event::factory()->for($registrationForm)->create(['start_date' => now()->addWeek(), 'end_date' => now()->addWeek()]);
        $this->actingAsRole(AdminRole::EventAdministrator);

        $this->delete('admin/registration-forms/'.$registrationForm->id)->assertSessionHas('error');

        $this->assertNotSoftDeleted($registrationForm);
    }

    #[Test]
    public function a_form_of_past_events_is_removed_and_their_exports_keep_the_answers(): void
    {
        $registrationForm = RegistrationForm::factory()->has(RegistrationFormField::factory(), 'fields')->create();
        $field = $registrationForm->fields->sole();
        $event = Event::factory()->for($registrationForm)->create(['start_date' => now()->subWeek(), 'end_date' => now()->subWeek(), 'registration_deadline' => now()->subWeeks(2)]);
        $event->registrations()->create(['user_id' => User::factory()->create()->id, 'registration_form_id' => $registrationForm->id])
            ->answers()->create(['registration_form_field_id' => $field->id, 'question' => $field->name, 'value' => 'An answer']);
        $this->actingAsRole(AdminRole::EventAdministrator);

        $this->delete('admin/registration-forms/'.$registrationForm->id)->assertRedirect(route('admin.registration-forms.index'));

        $this->assertSoftDeleted($registrationForm);
        $export = new EventRegistrationsExport($event->refresh());
        $this->assertContains($field->name, $export->headings());
        $this->assertContains('An answer', $export->map($event->registrations()->with(['user', 'answers'])->sole()));
    }
}
