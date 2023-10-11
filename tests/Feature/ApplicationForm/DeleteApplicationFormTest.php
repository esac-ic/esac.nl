<?php

namespace Tests\Feature\ApplicationForm;


use App\Models\ApplicationForm\ApplicationForm;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use TestCase;

class DeleteApplicationFormTest extends TestCase
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
    public function delete_application_form_as_content_administrator(): void
    {
        $response = $this->delete($this->url);

        $response->assertStatus(302);

        $this->assertNull(ApplicationForm::find($this->applicationForm->id));
    }

    /** @test */
    public function delete_application_form_as_activity_administrator(): void
    {
        $this->user->roles()->sync([Config::get('constants.Activity_administrator')]);

        $response = $this->delete($this->url);

        $response->assertStatus(302);

        $this->assertNull(ApplicationForm::find($this->applicationForm->id));
    }

    /** @test */
    public function delete_application_form_as_administrator_should_return_403(): void
    {
        $this->user->roles()->sync([Config::get('constants.Administrator')]);

        $response = $this->delete($this->url);

        $response->assertStatus(403);
    }

    /** @test */
    public function delete_application_form_as_certificate_administrator_should_return_403(): void
    {
        $this->user->roles()->sync([Config::get('constants.Certificate_administrator')]);

        $response = $this->delete($this->url);

        $response->assertStatus(403);

    }
}
