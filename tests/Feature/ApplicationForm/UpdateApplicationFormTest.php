<?php

namespace Tests\Feature;

use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationFormRowOption;
use App\User;
use Artisan;
use Config;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use TestCase;

class UpdateApplicationFormTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var User
     */
    private $user;

    /**
     * @var ApplicationForm
     */
    private $applicationForm;

    /**
     * @var string
     */
    private $url = 'beheer/applicationForms';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->applicationForm = factory(ApplicationForm::class)->create();
        $this->user = $user = factory(User::class)->create();
        $this->url .= "/" . $this->applicationForm->id;

        $user->roles()->attach(Config::get('constants.Content_administrator'));
        $this->be($user);

        session()->start();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        Artisan::call('migrate:refresh');
        parent::tearDown();
    }

    /** @test */
    public function update_application_form_as_content_administrator(): void
    {
        $applicationFormRow = factory(ApplicationFormRow::class)->create([
            'application_form_id' => $this->applicationForm->id,
        ]);
        $applicationFormRowOption = factory(ApplicationFormRowOption::class)->create([
            'application_form_row_id' => $applicationFormRow->id,
        ]);
        factory(ApplicationFormRowOption::class)->create([
            'application_form_row_id' => $applicationFormRow->id,
        ]);

        $body = [
            'nl_name' => 'test nl name',
            'en_name' => 'test en name',
            'rows' => [
                [
                    'id' => $applicationFormRow->id,
                    'nl_name' => 'Vraag 2',
                    'en_name' => 'Question 2',
                    'type' => ApplicationFormRow::FORM_TYPE_CHECK_BOX,
                    'required' => true,
                    'options' => [
                        [
                            'id' => $applicationFormRowOption->id,
                            'value' => 1,
                            'nl_name' => 'optie 1',
                            'en_name' => 'Option 1'
                        ],
                        [
                            'value' => 3,
                            'nl_name' => 'optie 2',
                            'en_name' => 'Option 2'
                        ],
                        [
                            'value' => 16,
                            'nl_name' => 'optie 3',
                            'en_name' => 'Option 3'
                        ]
                    ]
                ],
                [
                    'nl_name' => 'Vraag 1',
                    'en_name' => 'Question 1',
                    'type' => ApplicationFormRow::FORM_TYPE_NUMBER,
                ],
            ]
        ];

        $response = $this->put($this->url, $body);

        $response->assertStatus(302);

        $this->applicationForm->refresh();

        $this->assertApplicationForm($this->applicationForm, $body);
    }

    /** @test */
    public function update_application_form_as_administrator_should_return_403(): void
    {
        $this->user->roles()->sync([Config::get('constants.Administrator')]);

        $body = [
            'nl_name' => 'test nl name',
            'en_name' => 'test en name',
            'rows' => [
                [
                    'nl_name' => 'Vraag 1',
                    'en_name' => 'Question 1',
                    'type' => ApplicationFormRow::FORM_TYPE_NUMBER,
                ],
                [
                    'nl_name' => 'Vraag 2',
                    'en_name' => 'Question 2',
                    'type' => ApplicationFormRow::FORM_TYPE_CHECK_BOX,
                    'required' => true
                ]
            ]
        ];

        $response = $this->put($this->url, $body);

        $response->assertStatus(403);
    }

    /** @test */
    public function update_application_form_as_certificate_administrator_should_return_403(): void
    {
        $this->user->roles()->sync([Config::get('constants.Certificate_administrator')]);

        $body = [
            'nl_name' => 'test nl name',
            'en_name' => 'test en name',
            'rows' => [
                [
                    'nl_name' => 'Vraag 1',
                    'en_name' => 'Question 1',
                    'type' => ApplicationFormRow::FORM_TYPE_NUMBER,
                ],
                [
                    'nl_name' => 'Vraag 2',
                    'en_name' => 'Question 2',
                    'type' => ApplicationFormRow::FORM_TYPE_CHECK_BOX,
                    'required' => true
                ]
            ]
        ];

        $response = $this->put($this->url, $body);

        $response->assertStatus(403);
    }

    /**
     * @param ApplicationForm $applicationForm
     * @param array $data
     */
    private function assertApplicationForm(ApplicationForm $applicationForm, array $data){
        $this->assertEquals($data['nl_name'], $applicationForm->applicationFormName->NL_text);
        $this->assertEquals($data['en_name'], $applicationForm->applicationFormName->EN_text);
        $this->assertCount(count($data['rows']), $applicationForm->applicationFormRows);

        for($i=0; $i < count($applicationForm->applicationFormRows); $i++) {
            $rowData = $data['rows'][$i];
            $row = $applicationForm->applicationFormRows[$i];

            $this->assertEquals($rowData['nl_name'], $row->applicationFormRowName->NL_text);
            $this->assertEquals($rowData['en_name'], $row->applicationFormRowName->EN_text);
            $this->assertEquals($rowData['type'], $row->type);
            $this->assertEquals(array_key_exists('required', $rowData), $row->required);
        }
    }
}
