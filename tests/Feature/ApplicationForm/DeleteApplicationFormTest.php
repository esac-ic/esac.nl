<?php

namespace Tests\Feature\ApplicationForm;

use App\Models\ApplicationForm\ApplicationForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteApplicationFormTest extends TestCase
{
    use RefreshDatabase;

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
        $this->applicationForm = ApplicationForm::factory()->create();
        $this->user = $user = User::factory()->create();
        $this->url .= "/" . $this->applicationForm->id;

        $user->roles()->attach(Config::get('constants.Content_administrator'));
        $this->be($user);

        session()->start();
    }

    #[Test]
    public function delete_application_form_as_content_administrator(): void
    {
        $response = $this->delete($this->url);

        $response->assertStatus(302);

        $this->assertNull(ApplicationForm::find($this->applicationForm->id));
    }

    #[Test]
    public function delete_application_form_as_activity_administrator(): void
    {
        $this->user->roles()->sync([Config::get('constants.Activity_administrator')]);

        $response = $this->delete($this->url);

        $response->assertStatus(302);

        $this->assertNull(ApplicationForm::find($this->applicationForm->id));
    }

    #[Test]
    public function delete_application_form_as_administrator_should_return_403(): void
    {
        $this->user->roles()->sync([Config::get('constants.Administrator')]);

        $response = $this->delete($this->url);

        $response->assertStatus(403);
    }

    #[Test]
    public function delete_application_form_as_certificate_administrator_should_return_403(): void
    {
        $this->user->roles()->sync([Config::get('constants.Certificate_administrator')]);

        $response = $this->delete($this->url);

        $response->assertStatus(403);

    }
}
