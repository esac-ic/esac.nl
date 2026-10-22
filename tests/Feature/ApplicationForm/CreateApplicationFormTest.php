<?php

namespace Tests\Feature\ApplicationForm;

use App\Enums\Role;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
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
                ['name' => 'Question 1', 'type' => ApplicationFormRow::FORM_TYPE_NUMBER],
                ['name' => 'Question 2', 'type' => ApplicationFormRow::FORM_TYPE_CHECK_BOX, 'required' => true],
                [
                    'name' => 'Question 3',
                    'type' => ApplicationFormRow::FORM_TYPE_RADIO,
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
    public function application_form_managers_can_create_an_application_form(Role $role): void
    {
        $this->actingAsRole($role);

        $this->post('beheer/application-forms', $this->body())->assertRedirect();

        $this->assertApplicationForm(ApplicationForm::sole(), $this->body());
    }

    #[Test]
    #[DataProvider('otherRoles')]
    public function other_roles_can_not_create_an_application_form(Role $role): void
    {
        $this->actingAsRole($role);

        $this->post('beheer/application-forms', $this->body())->assertForbidden();

        $this->assertDatabaseEmpty(ApplicationForm::class);
    }
}
