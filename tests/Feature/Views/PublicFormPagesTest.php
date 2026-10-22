<?php

namespace Tests\Feature\Views;

use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\MenuItem;
use App\Models\NewsItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test the user facing public forms
 */
class PublicFormPagesTest extends TestCase
{
    use RefreshDatabase, InspectsForms;

    protected function setUp(): void
    {
        parent::setUp();
        session()->start();
    }

    private function createPage(string $urlName, bool $login = false): void
    {
        MenuItem::forceCreate([
            'name' => $urlName,
            'url_name' => $urlName,
            'login' => $login,
            'editable' => true,
            'deletable' => false,
            'menu_item' => true,
            'content' => "$urlName content",
        ]);
    }

    #[Test]
    public function the_signup_form_renders()
    {
        $this->createPage(MenuItem::SUBSCRIBEURL);
        $response = $this->get('signup')->assertOk();

        $this->assertForm($response, 'POST', 'signup');
        $this->assertSelected($response, 'country', 'NL');
        $this->assertSelected($response, 'emergency_country', 'NL');
        $this->assertChecked($response, 'privacy_policy', false);
        $this->assertChecked($response, 'incasso', false);
        $this->assertChecked($response, 'termsconditions', false);
    }

    #[Test]
    public function the_signup_form_keeps_the_submitted_values()
    {
        $this->createPage(MenuItem::SUBSCRIBEURL);

        $signup = $this->get('signup');
        $checked = fn (string $name) => $this->field($signup, $name)->getAttribute('value');

        $response = $this->from('signup')->followingRedirects()->post('signup', [
            'email' => 'piet@example.com',
            'password' => 'geheim123',
            'country' => 'BE',
            'emergency_country' => 'DE',
            'privacy_policy' => $checked('privacy_policy'),
            'incasso' => $checked('incasso'),
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $this->assertFieldValue($response, 'email', 'piet@example.com');
        $this->assertFieldValue($response, 'password', '');
        $this->assertSelected($response, 'country', 'BE');
        $this->assertSelected($response, 'emergency_country', 'DE');
        $this->assertChecked($response, 'privacy_policy');
        $this->assertChecked($response, 'incasso');
        $this->assertChecked($response, 'termsconditions', false);
    }

    #[Test]
    public function the_login_form_keeps_the_email_after_a_failed_login()
    {
        $this->assertForm($this->get('login')->assertOk(), 'POST', 'login');

        $user = User::factory()->create();
        $response = $this->from('login')->followingRedirects()->post('login', [
            'email' => $user->email,
            'password' => 'wrong',
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $this->assertGuest();
        // Fortify lowercases the email before logging in
        $this->assertFieldValue($response, 'email', Str::lower($user->email));
    }

    #[Test]
    public function the_password_reset_forms_render()
    {
        $this->assertForm($this->get('password/reset')->assertOk(), 'POST', 'password/email');

        $response = $this->get('password/reset/abc123')->assertOk();

        $this->assertForm($response, 'POST', 'password/reset');
        $this->assertFieldValue($response, 'token', 'abc123');
    }

    #[Test]
    public function the_registration_form_renders()
    {
        $this->be(User::factory()->create());
        $agendaItem = AgendaItem::factory()->create([
            'application_form_id' => ApplicationForm::factory()->create()->id,
            'subscription_end_date' => now()->addWeek(),
        ]);

        $response = $this->get("forms/$agendaItem->id")->assertOk();

        $this->assertForm($response, 'POST', "forms/$agendaItem->id");
    }

    #[Test]
    public function the_zekeringen_form_renders()
    {
        $this->createPage(MenuItem::ZEKERINGURL, true);
        $this->be(User::factory()->create());

        $response = $this->get('zekeringen')->assertOk();

        $this->assertForm($response, 'POST', 'api/zekeringen');
        $this->assertFieldValue($response, 'text', '');
    }

    #[Test]
    public function the_news_page_shortens_long_items()
    {
        $this->createPage(MenuItem::NEWSURL);
        NewsItem::factory()->create(['text' => '<p>' . str_repeat('a', 200) . '</p>']);

        $response = $this->get('news')->assertOk();

        $response->assertSee(str_repeat('a', 150 - strlen('<p>')) . '...', false);
    }
}
