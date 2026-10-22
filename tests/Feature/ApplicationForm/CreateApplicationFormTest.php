<?php

namespace Tests\Feature\ApplicationForm;

use App\Enums\AdminRole;
use App\Enums\FormRowType;
use App\Models\ApplicationForm\ApplicationForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\ManagesApplicationForms;
use Tests\TestCase;

class CreateApplicationFormTest extends TestCase
{
    use ManagesApplicationForms;
    use RefreshDatabase;

    private function body(): array
    {
        return [
            'name' => 'test name',
            'rows' => [
                ['name' => 'Question 1', 'type' => FormRowType::Number],
                ['name' => 'Question 2', 'type' => FormRowType::CheckBox, 'required' => true],
                [
                    'name' => 'Question 3',
                    'type' => FormRowType::Radio,
                    'required' => true,
                    'options' => [
                        ['value' => 1, 'name' => 'Option 1'],
                        ['value' => 3, 'name' => 'Option 2'],
                        ['value' => 16, 'name' => 'Option 3'],
                    ],
                ],
            ],
        ];
    }

    #[Test]
    #[DataProvider('managingRoles')]
    public function application_form_managers_can_create_an_application_form(AdminRole $role): void
    {
        $this->actingAsRole($role);

        $this->post('admin/application-forms', $this->body())->assertRedirect();

        $this->assertApplicationForm(ApplicationForm::sole(), $this->body());
    }

    #[Test]
    #[DataProvider('otherRoles')]
    public function other_roles_can_not_create_an_application_form(AdminRole $role): void
    {
        $this->actingAsRole($role);

        $this->post('admin/application-forms', $this->body())->assertForbidden();

        $this->assertDatabaseEmpty(ApplicationForm::class);
    }
}
