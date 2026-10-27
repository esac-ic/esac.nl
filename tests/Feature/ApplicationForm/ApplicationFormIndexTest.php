<?php

namespace Tests\Feature\ApplicationForm;

use App\Models\ApplicationForm\ApplicationForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Class ApplicationFormIndexTest
 * @package Tests\Feature
 */
class ApplicationFormIndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ApplicationForm
     */
    private $applicationForm;

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
        $this->user = $user = User::factory()->create();

        $user->roles()->attach(Config::get('constants.Content_administrator'));
        $this->be($user);

        $this->applicationForm = ApplicationForm::factory()->create();

        session()->start();
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
        $this->user->roles()->sync([Config::get('constants.Activity_administrator')]);

        $response = $this->get(self::URL);

        $response->assertStatus(200);
    }

    #[Test]
    public function an_administrator_can_not_view_the_page(): void
    {
        $this->user->roles()->sync([Config::get('constants.Administrator')]);

        $response = $this->get(self::URL);

        $response->assertStatus(403);
    }

    #[Test]
    public function a_certificate_administrator_can_not_view_the_page(): void
    {
        $this->user->roles()->sync([Config::get('constants.Certificate_administrator')]);

        $response = $this->get(self::URL);

        $response->assertStatus(403);
    }

    #[Test]
    public function an_user_without_views_can_not_view_the_page(): void
    {
        $this->user->roles()->sync([Config::get('constants.Certificate_administrator')]);

        $response = $this->get(self::URL);

        $response->assertStatus(403);
    }
}
