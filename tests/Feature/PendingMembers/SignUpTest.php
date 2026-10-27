<?php

namespace Tests\Feature\PendingMembers;

use App\Events\MemberSignedUp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SignUpTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_guest_can_sign_up_as_a_pending_member()
    {
        Event::fake();
        Http::fake(['www.google.com/recaptcha/*' => Http::response(['success' => true])]);
        $details = [
            'email' => 'initial@esac.nl',
            'first_name' => 'Initial',
            'last_name' => 'Initial',
            'street' => 'Initial',
            'house_number' => '1',
            'city' => 'Initial',
            'zipcode' => '1111AA',
            'country' => 'NL',
            'phone_number' => '123456789',
            'emergency_number' => '123456789',
            'emergency_house_number' => '1',
            'emergency_street' => 'Initial',
            'emergency_city' => 'Initial',
            'emergency_zipcode' => '1111AA',
            'emergency_country' => 'NL',
            'birthday' => '1990-01-01',
            'iban' => '123456789',
        ];

        $this->post(route('sign-up.store'), [
            ...$details,
            'password' => 'a-password',
            'password_confirmation' => 'a-password',
            'g-recaptcha-response' => 'True',
            'direct_debit' => '1',
            'privacy_policy' => '1',
            'terms_and_conditions' => '1',
        ])->assertRedirectToRoute('sign-up.create');

        Event::assertDispatched(MemberSignedUp::class);
        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseHas(User::class, [...$details, 'birthday' => '1990-01-01 00:00:00', 'direct_debit' => 1]);
    }

    #[Test]
    public function a_sign_up_with_a_failed_captcha_is_rejected()
    {
        Http::fake(['www.google.com/recaptcha/*' => Http::response(['success' => false])]);
        Event::fake();

        $this->post(route('sign-up.store'), ['g-recaptcha-response' => 'invalid'])
            ->assertInvalid(['g-recaptcha-response' => "'I'm not a robot' validation failed, please try again"]);

        Event::assertNotDispatched(MemberSignedUp::class);
        Http::assertSent(fn ($request) => $request['response'] === 'invalid');
    }

    #[Test]
    public function a_sign_up_on_a_blocked_email_domain_is_rejected()
    {
        Http::fake(['www.google.com/recaptcha/*' => Http::response(['success' => true])]);

        $this->post(route('sign-up.store'), ['email' => 'student@student.tue.nl'])
            ->assertInvalid(['email' => 'Email addresses ending in @student.tue.nl']);
    }
}
