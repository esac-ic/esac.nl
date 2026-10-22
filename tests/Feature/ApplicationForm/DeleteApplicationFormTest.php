<?php

namespace Tests\Feature\ApplicationForm;

use App\Enums\Role;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteApplicationFormTest extends TestCase
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
    public function delete_application_form_as_content_administrator(): void
    {
        $response = $this->delete($this->url);

        $response->assertStatus(302);

        $this->assertSoftDeleted($this->applicationForm);
    }

    #[Test]
    public function delete_application_form_as_activity_administrator(): void
    {
        $this->user->roles()->sync([Role::ActivityAdministrator->value]);

        $response = $this->delete($this->url);

        $response->assertStatus(302);

        $this->assertSoftDeleted($this->applicationForm);
    }

    #[Test]
    public function delete_application_form_as_administrator_should_return_403(): void
    {
        $this->user->roles()->sync([Role::Administrator->value]);

        $response = $this->delete($this->url);

        $response->assertStatus(403);
    }

    #[Test]
    public function delete_application_form_as_certificate_administrator_should_return_403(): void
    {
        $this->user->roles()->sync([Role::CertificateAdministrator->value]);

        $response = $this->delete($this->url);

        $response->assertStatus(403);

    }
}
