<?php

namespace Tests\Feature\ApplicationForm;

use App\Enums\AdminRole;
use App\Models\ApplicationForm\ApplicationForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\ManagesApplicationForms;
use Tests\TestCase;

class DeleteApplicationFormTest extends TestCase
{
    use ManagesApplicationForms;
    use RefreshDatabase;

    #[Test]
    #[DataProvider('managingRoles')]
    public function application_form_managers_can_delete_an_application_form(AdminRole $role): void
    {
        $applicationForm = ApplicationForm::factory()->create();
        $this->actingAsRole($role);

        $this->delete('admin/application-forms/'.$applicationForm->id)->assertRedirect();

        $this->assertSoftDeleted($applicationForm);
    }

    #[Test]
    #[DataProvider('otherRoles')]
    public function other_roles_can_not_delete_an_application_form(AdminRole $role): void
    {
        $applicationForm = ApplicationForm::factory()->create();
        $this->actingAsRole($role);

        $this->delete('admin/application-forms/'.$applicationForm->id)->assertForbidden();
    }
}
