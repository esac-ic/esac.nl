<?php

namespace Tests\Feature\ApplicationForm;

use App\Enums\Role;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApplicationFormIndexTest extends TestCase
{
    use RefreshDatabase;

    private $applicationForm;

    private $user;

    const URL = 'beheer/applicationForms';

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = User::factory()->create();

        $user->roles()->attach(Role::ContentAdministrator->value);
        $this->be($user);

        $this->applicationForm = ApplicationForm::factory()->create();
    }

    #[Test]
    public function a_content_administrator_can_view_the_page(): void
    {
        $response = $this->get(self::URL);

        $response->assertStatus(200);
    }

    #[Test]
    public function an_activity_administrator_can_view_the_page(): void
    {
        $this->user->roles()->sync([Role::ActivityAdministrator->value]);

        $response = $this->get(self::URL);

        $response->assertStatus(200);
    }

    #[Test]
    public function an_administrator_can_not_view_the_page(): void
    {
        $this->user->roles()->sync([Role::Administrator->value]);

        $response = $this->get(self::URL);

        $response->assertStatus(403);
    }

    #[Test]
    public function a_certificate_administrator_can_not_view_the_page(): void
    {
        $this->user->roles()->sync([Role::CertificateAdministrator->value]);

        $response = $this->get(self::URL);

        $response->assertStatus(403);
    }

    #[Test]
    public function an_user_without_views_can_not_view_the_page(): void
    {
        $this->user->roles()->sync([Role::CertificateAdministrator->value]);

        $response = $this->get(self::URL);

        $response->assertStatus(403);
    }
}
