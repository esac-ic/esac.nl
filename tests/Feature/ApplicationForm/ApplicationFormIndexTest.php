<?php

namespace Tests\Feature\ApplicationForm;

use App\Enums\Role;
use App\Models\ApplicationForm\ApplicationForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\ManagesApplicationForms;
use Tests\TestCase;

class ApplicationFormIndexTest extends TestCase
{
    use ManagesApplicationForms;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        ApplicationForm::factory()->create();
    }

    #[Test]
    #[DataProvider('managingRoles')]
    public function application_form_managers_can_view_the_page(Role $role): void
    {
        $this->actingAsRole($role);

        $this->get('beheer/applicationForms')->assertOk();
    }

    #[Test]
    #[DataProvider('otherRoles')]
    public function other_roles_can_not_view_the_page(Role $role): void
    {
        $this->actingAsRole($role);

        $this->get('beheer/applicationForms')->assertForbidden();
    }

    #[Test]
    public function an_user_without_roles_can_not_view_the_page(): void
    {
        $this->actingAsRole();

        $this->get('beheer/applicationForms')->assertForbidden();
    }
}
