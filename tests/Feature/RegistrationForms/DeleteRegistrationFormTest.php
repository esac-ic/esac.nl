<?php

namespace Tests\Feature\RegistrationForms;

use App\Enums\AdminRole;
use App\Models\RegistrationForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\ManagesRegistrationForms;
use Tests\TestCase;

class DeleteRegistrationFormTest extends TestCase
{
    use ManagesRegistrationForms;
    use RefreshDatabase;

    #[Test]
    #[DataProvider('managingRoles')]
    public function registration_form_managers_can_delete_a_registration_form(AdminRole $role): void
    {
        $registrationForm = RegistrationForm::factory()->create();
        $this->actingAsRole($role);

        $this->delete('admin/registration-forms/'.$registrationForm->id)->assertRedirect();

        $this->assertSoftDeleted($registrationForm);
    }

    #[Test]
    #[DataProvider('otherRoles')]
    public function other_roles_can_not_delete_a_registration_form(AdminRole $role): void
    {
        $registrationForm = RegistrationForm::factory()->create();
        $this->actingAsRole($role);

        $this->delete('admin/registration-forms/'.$registrationForm->id)->assertForbidden();
    }
}
