<?php

namespace Tests\Feature\Http;

use App\Enums\AdminRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RoutingTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_home_page_is_served_at_the_root()
    {
        $this->get('/')->assertOk();
    }

    #[Test]
    public function the_health_check_responds()
    {
        $this->get('/up')->assertOk();
    }

    #[Test]
    public function an_unknown_page_shows_the_not_found_page()
    {
        $this->get('/does-not-exist')->assertNotFound()->assertSee('Page not found')->assertSee('<nav', false);
    }

    #[Test]
    public function a_forbidden_page_shows_the_no_access_page()
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/members/create')
            ->assertForbidden()
            ->assertSee('You do not have access to this page.')
            ->assertSee('<nav', false);
    }

    #[Test]
    public function a_member_who_is_logged_in_is_sent_home_from_the_login_page()
    {
        $this->actingAs(User::factory()->create())->get('/login')->assertRedirect('/');
    }

    #[Test]
    public function api_errors_are_json()
    {
        $this->get('api/does-not-exist')->assertNotFound()->assertHeader('Content-Type', 'application/json');
        $this->get('api/zekeringen')->assertUnauthorized()->assertJson(['message' => 'Unauthenticated.']);
    }

    #[Test]
    public function the_api_uses_the_session_of_the_site()
    {
        $this->actingAs(User::factory()->create())
            ->getJson('api/zekeringen')
            ->assertOk();
    }

    #[Test]
    public function api_writes_need_the_csrf_token_of_the_page()
    {
        $user = User::factory()->withRole(AdminRole::EventAdministrator)->create();
        // Laravel skips the CSRF check in tests
        $this->app['env'] = 'local';

        $this->actingAs($user)->postJson('api/zekeringen', ['text' => 'no token'])->assertStatus(419);

        $this->withSession(['_token' => 'page-token'])
            ->postJson('api/zekeringen', ['text' => 'with token'], ['X-CSRF-TOKEN' => 'page-token'])
            ->assertCreated();
    }
}
