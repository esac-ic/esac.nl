<?php

namespace Tests\Feature\PendingUser;

use App\Events\PendingUserCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StorePendingUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_pending_member()
    {
        $requestData = [
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
            'password' => 'a-password',
            'password_confirmation' => 'a-password',
            'g-recaptcha-response' => 'True',
            'incasso' => 'True',
            'privacy_policy' => 'True',
            'termsconditions' => 'True',
        ];

        Event::fake();
        Http::fake(['www.google.com/recaptcha/*' => Http::response(['success' => true])]);

        $this->assertEquals(0, User::all()->count());

        $response = $this->post(route('user.signup'), $requestData);

        $response->assertRedirect(route('front-end.signup'));
        Event::assertDispatched(PendingUserCreated::class);
        $this->assertEquals(1, User::all()->count());

        $user = User::first();

        $this->assertEquals('initial@esac.nl', $user->email);
        $this->assertEquals('Initial', $user->firstname);
        $this->assertEquals('Initial', $user->lastname);
        $this->assertEquals('Initial', $user->street);
        $this->assertEquals('1', $user->house_number);
        $this->assertEquals('Initial', $user->city);
        $this->assertEquals('1111AA', $user->zipcode);
        $this->assertEquals('Initial', $user->country);
        $this->assertEquals('123456789', $user->phone_number);
        $this->assertEquals('123456789', $user->emergency_number);
        $this->assertEquals('1', $user->emergency_house_number);
        $this->assertEquals('Initial', $user->emergency_street);
        $this->assertEquals('Initial', $user->emergency_city);
        $this->assertEquals('1111AA', $user->emergency_zipcode);
        $this->assertEquals('Initial', $user->emergency_country);
        $this->assertEquals('123456789', $user->iban);

        $this->assertEquals(new Carbon('1990-01-01'), $user->birthday);
        $this->assertEquals(1, $user->incasso);

    }

    public function test_a_failed_captcha_is_rejected()
    {
        Http::fake(['www.google.com/recaptcha/*' => Http::response(['success' => false])]);
        Event::fake();

        $response = $this->post(route('user.signup'), ['g-recaptcha-response' => 'invalid']);

        $response->assertSessionHasErrors(['g-recaptcha-response' => "'I'm not a robot' validation failed, please try again"]);
        Event::assertNotDispatched(PendingUserCreated::class);
        Http::assertSent(fn ($request) => $request['response'] === 'invalid');
    }
}
