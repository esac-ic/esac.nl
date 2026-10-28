<?php

namespace Tests\Feature\ApplicationForm;

use App\Enums\Role;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationFormRowOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateApplicationFormTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    private $applicationForm;

    private $url = 'beheer/applicationForms';

    protected function setUp(): void
    {
        parent::setUp();
        $this->applicationForm = ApplicationForm::factory()->create();
        $this->user = $user = User::factory()->create();
        $this->url .= '/'.$this->applicationForm->id;

        $user->roles()->attach(Role::ContentAdministrator->value);
        $this->be($user);
    }

    #[Test]
    public function update_application_form_as_content_administrator(): void
    {
        $applicationFormRow = ApplicationFormRow::factory()->create([
            'application_form_id' => $this->applicationForm->id,
        ]);
        $applicationFormRowOption = ApplicationFormRowOption::factory()->create([
            'application_form_row_id' => $applicationFormRow->id,
        ]);
        ApplicationFormRowOption::factory()->create([
            'application_form_row_id' => $applicationFormRow->id,
        ]);

        $body = [
            'name' => 'test name',
            'rows' => [
                [
                    'id' => $applicationFormRow->id,
                    'name' => 'Question 2',
                    'type' => ApplicationFormRow::FORM_TYPE_CHECK_BOX,
                    'required' => true,
                    'options' => [
                        [
                            'id' => $applicationFormRowOption->id,
                            'value' => 1,
                            'name' => 'Option 1',
                        ],
                        [
                            'value' => 3,
                            'name' => 'Option 2',
                        ],
                        [
                            'value' => 16,
                            'name' => 'Option 3',
                        ],
                    ],
                ],
                [
                    'name' => 'Question 1',
                    'type' => ApplicationFormRow::FORM_TYPE_NUMBER,
                ],
            ],
        ];

        $response = $this->put($this->url, $body);

        $response->assertStatus(302);

        $this->applicationForm->refresh();

        $this->assertApplicationForm($this->applicationForm, $body);
    }

    #[Test]
    public function update_application_form_as_administrator_should_return_403(): void
    {
        $this->user->roles()->sync([Role::Administrator->value]);

        $body = [
            'name' => 'test name',
            'rows' => [
                [
                    'name' => 'Question 1',
                    'type' => ApplicationFormRow::FORM_TYPE_NUMBER,
                ],
                [
                    'name' => 'Question 2',
                    'type' => ApplicationFormRow::FORM_TYPE_CHECK_BOX,
                    'required' => true,
                ],
            ],
        ];

        $response = $this->put($this->url, $body);

        $response->assertStatus(403);
    }

    #[Test]
    public function update_application_form_as_certificate_administrator_should_return_403(): void
    {
        $this->user->roles()->sync([Role::CertificateAdministrator->value]);

        $body = [
            'name' => 'test name',
            'rows' => [
                [
                    'name' => 'Question 1',
                    'type' => ApplicationFormRow::FORM_TYPE_NUMBER,
                ],
                [
                    'name' => 'Question 2',
                    'type' => ApplicationFormRow::FORM_TYPE_CHECK_BOX,
                    'required' => true,
                ],
            ],
        ];

        $response = $this->put($this->url, $body);

        $response->assertStatus(403);
    }

    private function assertApplicationForm(ApplicationForm $applicationForm, array $data)
    {
        $this->assertEquals($data['name'], $applicationForm->name);
        $this->assertCount(count($data['rows']), $applicationForm->applicationFormRows);

        for ($i = 0; $i < count($applicationForm->applicationFormRows); $i++) {
            $rowData = $data['rows'][$i];
            $row = $applicationForm->applicationFormRows[$i];

            $this->assertEquals($rowData['name'], $row->name);
            $this->assertEquals($rowData['type'], $row->type);
            $this->assertEquals(array_key_exists('required', $rowData), $row->required);
        }
    }
}
