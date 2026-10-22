<?php

namespace Tests\Feature\ApplicationForm;

use App\Enums\FormRowType;
use App\Enums\Role;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationFormRowOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\ManagesApplicationForms;
use Tests\TestCase;

class UpdateApplicationFormTest extends TestCase
{
    use ManagesApplicationForms;
    use RefreshDatabase;

    private ApplicationForm $applicationForm;

    private string $url;

    protected function setUp(): void
    {
        parent::setUp();
        $this->applicationForm = ApplicationForm::factory()->create();
        $this->url = 'beheer/application-forms/'.$this->applicationForm->id;
    }

    #[Test]
    public function update_application_form_as_content_administrator(): void
    {
        $this->actingAsRole(Role::ContentAdministrator);
        $row = ApplicationFormRow::factory()->create(['application_form_id' => $this->applicationForm->id]);
        [$option] = ApplicationFormRowOption::factory(2)->create(['application_form_row_id' => $row->id]);

        $body = [
            'name' => 'test name',
            'rows' => [
                [
                    'id' => $row->id,
                    'name' => 'Question 2',
                    'type' => FormRowType::CheckBox,
                    'required' => true,
                    'options' => [
                        ['id' => $option->id, 'value' => 1, 'name' => 'Option 1'],
                        ['value' => 3, 'name' => 'Option 2'],
                        ['value' => 16, 'name' => 'Option 3'],
                    ],
                ],
                ['name' => 'Question 1', 'type' => FormRowType::Number],
            ],
        ];

        $this->put($this->url, $body)->assertRedirect();

        $this->assertApplicationForm($this->applicationForm->refresh(), $body);
    }

    #[Test]
    #[DataProvider('otherRoles')]
    public function other_roles_can_not_update_an_application_form(Role $role): void
    {
        $this->actingAsRole($role);

        $this->put($this->url, [
            'name' => 'test name',
            'rows' => [['name' => 'Question 1', 'type' => FormRowType::Number]],
        ])->assertForbidden();
    }

    #[Test]
    public function a_question_can_be_made_mandatory_and_optional_again(): void
    {
        $this->actingAsRole(Role::ContentAdministrator);
        $row = ApplicationFormRow::factory()->create([
            'application_form_id' => $this->applicationForm->id,
            'type' => FormRowType::Text,
            'required' => false,
        ]);
        $question = ['id' => $row->id, 'name' => $row->name, 'type' => $row->type];

        // A browser posts a checked checkbox as "on" and leaves an unchecked one out.
        $this->put($this->url, ['name' => 'test name', 'rows' => [$question + ['required' => 'on']]])
            ->assertRedirectToRoute('beheer.application-forms.index');
        $this->assertTrue($row->fresh()->required);

        $this->put($this->url, ['name' => 'test name', 'rows' => [$question]])
            ->assertRedirectToRoute('beheer.application-forms.index');
        $this->assertFalse($row->fresh()->required);
    }
}
