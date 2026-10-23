<?php

namespace Tests\Feature\RegistrationForms;

use App\Enums\AdminRole;
use App\Models\RegistrationForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\ManagesRegistrationForms;
use Tests\TestCase;

class RegistrationFormIndexTest extends TestCase
{
    use ManagesRegistrationForms;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RegistrationForm::factory()->create();
    }

    #[Test]
    #[DataProvider('managingRoles')]
    public function registration_form_managers_can_view_the_page(AdminRole $role): void
    {
        $this->actingAsRole($role);

        $this->get('admin/registration-forms')->assertOk();
    }

    #[Test]
    #[DataProvider('otherRoles')]
    public function other_roles_can_not_view_the_page(AdminRole $role): void
    {
        $this->actingAsRole($role);

        $this->get('admin/registration-forms')->assertForbidden();
    }

    #[Test]
    public function a_member_without_roles_can_not_view_the_page(): void
    {
        $this->actingAsRole();

        $this->get('admin/registration-forms')->assertForbidden();
    }
}
