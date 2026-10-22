<?php

namespace Tests\Feature\RegistrationForms;

use App\Enums\AdminRole;
use App\Enums\FormFieldType;
use App\Models\RegistrationForm;
use App\Models\RegistrationFormField;
use App\Models\RegistrationFormFieldOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\ManagesRegistrationForms;
use Tests\TestCase;

class UpdateRegistrationFormTest extends TestCase
{
    use ManagesRegistrationForms;
    use RefreshDatabase;

    private RegistrationForm $registrationForm;

    private string $url;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registrationForm = RegistrationForm::factory()->create();
        $this->url = 'admin/registration-forms/'.$this->registrationForm->id;
    }

    #[Test]
    public function a_content_administrator_can_update_a_registration_form(): void
    {
        $this->actingAsRole(AdminRole::ContentAdministrator);
        $row = RegistrationFormField::factory()->create(['registration_form_id' => $this->registrationForm->id]);
        [$option] = RegistrationFormFieldOption::factory(2)->create(['registration_form_field_id' => $row->id]);

        $body = [
            'name' => 'test name',
            'rows' => [
                [
                    'id' => $row->id,
                    'name' => 'Question 2',
                    'type' => FormFieldType::Checkbox,
                    'required' => true,
                    'options' => [
                        ['id' => $option->id, 'value' => 1, 'name' => 'Option 1'],
                        ['value' => 3, 'name' => 'Option 2'],
                        ['value' => 16, 'name' => 'Option 3'],
                    ],
                ],
                ['name' => 'Question 1', 'type' => FormFieldType::Number],
            ],
        ];

        $this->put($this->url, $body)->assertRedirect();

        $this->assertRegistrationForm($this->registrationForm->refresh(), $body);
    }

    #[Test]
    #[DataProvider('otherRoles')]
    public function other_roles_can_not_update_a_registration_form(AdminRole $role): void
    {
        $this->actingAsRole($role);

        $this->put($this->url, [
            'name' => 'test name',
            'rows' => [['name' => 'Question 1', 'type' => FormFieldType::Number]],
        ])->assertForbidden();
    }

    #[Test]
    public function a_field_can_be_made_required_and_optional_again(): void
    {
        $this->actingAsRole(AdminRole::ContentAdministrator);
        $row = RegistrationFormField::factory()->create([
            'registration_form_id' => $this->registrationForm->id,
            'type' => FormFieldType::Text,
            'required' => false,
        ]);
        $question = ['id' => $row->id, 'name' => $row->name, 'type' => $row->type];

        // A browser posts a checked checkbox as "on" and leaves an unchecked one out.
        $this->put($this->url, ['name' => 'test name', 'rows' => [$question + ['required' => 'on']]])
            ->assertRedirectToRoute('admin.registration-forms.index');
        $this->assertTrue($row->fresh()->required);

        $this->put($this->url, ['name' => 'test name', 'rows' => [$question]])
            ->assertRedirectToRoute('admin.registration-forms.index');
        $this->assertFalse($row->fresh()->required);
    }
}
