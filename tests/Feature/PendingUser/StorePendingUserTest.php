<?php

namespace Tests\Feature\PendingUser;

use App\Events\PendingUserCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StorePendingUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_pending_member()
    {
        Event::fake();
        Http::fake(['www.google.com/recaptcha/*' => Http::response(['success' => true])]);
        $details = [
            'email' => 'initial@esac.nl',
            'firstname' => 'Initial',
            'lastname' => 'Initial',
            'street' => 'Initial',
            'house_number' => '1',
            'city' => 'Initial',
            'zipcode' => '1111AA',
            'country' => 'Initial',
            'phone_number' => '123456789',
            'emergency_number' => '123456789',
            'emergency_house_number' => '1',
            'emergency_street' => 'Initial',
            'emergency_city' => 'Initial',
            'emergency_zipcode' => '1111AA',
            'emergency_country' => 'Initial',
            'birthday' => '1990-01-01',
            'iban' => '123456789',
        ];

        $this->post(route('user.signup'), [
            ...$details,
            'password' => 'a-password',
            'password_confirmation' => 'a-password',
            'g-recaptcha-response' => 'True',
            'incasso' => 'True',
            'privacy_policy' => 'True',
            'termsconditions' => 'True',
        ])->assertRedirectToRoute('front-end.signup');

        Event::assertDispatched(PendingUserCreated::class);
        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseHas(User::class, [...$details, 'birthday' => '1990-01-01 00:00:00', 'incasso' => 1]);
    }

    public function test_a_failed_captcha_is_rejected()
    {
        Http::fake(['www.google.com/recaptcha/*' => Http::response(['success' => false])]);
        Event::fake();

        $this->post(route('user.signup'), ['g-recaptcha-response' => 'invalid'])
            ->assertInvalid(['g-recaptcha-response' => "'I'm not a robot' validation failed, please try again"]);

        Event::assertNotDispatched(PendingUserCreated::class);
        Http::assertSent(fn ($request) => $request['response'] === 'invalid');
    }
}
