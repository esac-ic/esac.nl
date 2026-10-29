<?php

namespace Tests\Feature\Views;

use App\Enums\PageSlug;
use App\Models\Event;
use App\Models\MenuItem;
use App\Models\NewsItem;
use App\Models\RegistrationForm;
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
    use InspectsForms, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        session()->start();
    }

    private function createPage(PageSlug $slug, bool $requiresLogin = false): void
    {
        MenuItem::forceCreate([
            'name' => $slug->value,
            'slug' => $slug->value,
            'requires_login' => $requiresLogin,
            'editable' => true,
            'deletable' => false,
            'show_in_menu' => true,
            'content' => "$slug->value content",
        ]);
    }

    #[Test]
    public function the_signup_form_renders()
    {
        $this->createPage(PageSlug::SignUp);
        $response = $this->get('sign-up')->assertOk();

        $this->assertForm($response, 'POST', 'sign-up');
        $this->assertSelected($response, 'country', 'NL');
        $this->assertSelected($response, 'emergency_country', 'NL');
        $this->assertChecked($response, 'privacy_policy', false);
        $this->assertChecked($response, 'direct_debit', false);
        $this->assertChecked($response, 'terms_and_conditions', false);
    }

    #[Test]
    public function the_signup_form_keeps_the_submitted_values()
    {
        $this->createPage(PageSlug::SignUp);

        $signup = $this->get('sign-up');
        $checked = fn (string $name) => $this->field($signup, $name)->getAttribute('value');

        $response = $this->from('sign-up')->followingRedirects()->post('sign-up', [
            'email' => 'piet@example.com',
            'password' => 'geheim123',
            'country' => 'BE',
            'emergency_country' => 'DE',
            'privacy_policy' => $checked('privacy_policy'),
            'direct_debit' => $checked('direct_debit'),
            '_token' => csrf_token(),
        ]);

        $response->assertOk();
        $this->assertFieldValue($response, 'email', 'piet@example.com');
        $this->assertFieldValue($response, 'password', '');
        $this->assertSelected($response, 'country', 'BE');
        $this->assertSelected($response, 'emergency_country', 'DE');
        $this->assertChecked($response, 'privacy_policy');
        $this->assertChecked($response, 'direct_debit');
        $this->assertChecked($response, 'terms_and_conditions', false);
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
        $this->assertForm($this->get('forgot-password')->assertOk(), 'POST', 'forgot-password');

        $response = $this->get('reset-password/abc123')->assertOk();

        $this->assertForm($response, 'POST', 'reset-password');
        $this->assertFieldValue($response, 'token', 'abc123');
    }

    #[Test]
    public function the_registration_form_renders()
    {
        $this->be(User::factory()->create());
        $event = Event::factory()->create([
            'registration_form_id' => RegistrationForm::factory()->create()->id,
            'registration_deadline' => now()->addWeek(),
        ]);

        $response = $this->get(route('events.registration.create', $event))->assertOk();

        $this->assertForm($response, 'POST', "events/$event->id/registration");
    }

    #[Test]
    public function the_zekeringen_form_renders()
    {
        $this->createPage(PageSlug::Zekeringen, true);
        $this->be(User::factory()->create());

        $response = $this->get('zekeringen')->assertOk();

        $this->assertForm($response, 'POST', 'api/zekeringen');
        $this->assertFieldValue($response, 'text', '');
    }

    #[Test]
    public function the_news_page_shortens_long_items()
    {
        $this->createPage(PageSlug::News);
        NewsItem::factory()->create(['body' => '<p>'.str_repeat('a', 200).'</p>']);

        $response = $this->get('news')->assertOk();

        $response->assertSee(str_repeat('a', 150 - strlen('<p>')).'...', false);
    }
}
