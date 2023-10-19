<?php

namespace Tests\Feature\ApplicationForm;

use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use TestCase;

/**
 * Class CreateApplicationFormTest
 * @package Tests\Feature
 */
class CreateApplicationFormTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    const URL = 'beheer/applicationForms';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = factory(User::class)->create();

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
    public function create_application_form_as_content_administrator(): void
    {
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
                [
                    'name' => 'Question 3',
                    'type' => ApplicationFormRow::FORM_TYPE_RADIO,
                    'required' => true,
                    'options' => [
                        [
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
            ],
        ];

        $response = $this->post(self::URL, $body);

        $response->assertStatus(302);

        $applicationForm = ApplicationForm::all()->last();

        $this->assertNotNull($applicationForm);
        $this->assertApplicationForm($applicationForm, $body);
    }

    /** @test */
    public function create_application_form_as_activity_administrator(): void
    {
        $this->user->roles()->sync([Config::get('constants.Activity_administrator')]);
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

        $response = $this->post(self::URL, $body);

        $response->assertStatus(302);

        $applicationForm = ApplicationForm::all()->last();

        $this->assertNotNull($applicationForm);
        $this->assertApplicationForm($applicationForm, $body);
    }

    /** @test */
    public function create_application_form_as_administrator_should_return_403(): void
    {
        $this->user->roles()->sync([Config::get('constants.Administrator')]);

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

        $response = $this->post(self::URL, $body);

        $response->assertStatus(403);
    }

    /** @test */
    public function create_application_form_as_certificate_administrator_should_return_403(): void
    {
        $this->user->roles()->sync([Config::get('constants.Certificate_administrator')]);

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

        $response = $this->post(self::URL, $body);

        $response->assertStatus(403);
    }

    /**
     * @param ApplicationForm $applicationForm
     * @param array $data
     */
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

            if (array_key_exists('options', $rowData)) {
                $optionDataItems = $rowData['options'];
                $options = $row->applicationFormRowOptions;

                $this->assertCount(count($optionDataItems), $options);
                for ($x = 0; $x < count($options); $x++) {
                    $optionData = $optionDataItems[$x];
                    $option = $options[$x];

                    $this->assertEquals($optionData['value'], $option->value);
                    $this->assertEquals($optionData['name'], $option->name);
                }
            }
        }
    }
}
