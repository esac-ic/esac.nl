<?php

namespace Tests\Feature\RegistrationForms;

use App\Enums\AdminRole;
use App\Enums\FormFieldType;
use App\Models\RegistrationForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\ManagesRegistrationForms;
use Tests\TestCase;

class CreateRegistrationFormTest extends TestCase
{
    use ManagesRegistrationForms;
    use RefreshDatabase;

    private function body(): array
    {
        return [
            'name' => 'test name',
            'rows' => [
                ['name' => 'Question 1', 'type' => FormFieldType::Number],
                ['name' => 'Question 2', 'type' => FormFieldType::Checkbox, 'required' => true],
                [
                    'name' => 'Question 3',
                    'type' => FormFieldType::Radio,
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
    public function registration_form_managers_can_create_a_registration_form(AdminRole $role): void
    {
        $this->actingAsRole($role);

        $this->post('admin/registration-forms', $this->body())->assertRedirect();

        $this->assertRegistrationForm(RegistrationForm::sole(), $this->body());
    }

    #[Test]
    #[DataProvider('otherRoles')]
    public function other_roles_can_not_create_a_registration_form(AdminRole $role): void
    {
        $this->actingAsRole($role);

        $this->post('admin/registration-forms', $this->body())->assertForbidden();

        $this->assertDatabaseEmpty(RegistrationForm::class);
    }
}
