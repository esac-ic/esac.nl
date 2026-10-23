<?php

namespace Tests\Feature\RegistrationForms;

use App\Enums\AdminRole;
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
}
