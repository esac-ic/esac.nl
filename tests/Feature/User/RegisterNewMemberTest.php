<?php

namespace Tests\Feature\User;

use App\Events\PendingUserCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterNewMemberTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Http::fake(['www.google.com/recaptcha/*' => Http::response(['success' => true])]);
    }

    #[Test]
    public function a_guest_can_register_a_new_user()
    {
        $body = [
            'email' => 'test@esac.nl',
            'firstname' => 'esac',
            'lastname' => 'esac last name',
            'street' => 'stastion street',
            'house_number' => 3,
            'city' => 'eindhoven',
            'zipcode' => '5781JH',
            'country' => 'NL',
            'phone_number' => 28743939,
            'emergency_number' => 112,
            'emergency_house_number' => '2a',
            'emergency_street' => 'nursery',
            'emergency_city' => 'dead',
            'emergency_zipcode' => '3473JP',
            'emergency_country' => 'NL',
            'birthday' => now()->subCenturies(2)->format('d-m-Y'),
            'iban' => 'BLNDj838474784848',
            'password' => 'a-password',
            'password_confirmation' => 'a-password',
            'incasso' => 1,
            'privacy_policy' => 1,
            'termsconditions' => 1,
            'g-recaptcha-response' => 'ikbenvalid',
        ];

        $this->post('/signup', $body)->assertRedirect();

        Event::assertDispatched(PendingUserCreated::class);
        $this->assertDatabaseHas(User::class, [
            ...Arr::except($body, ['birthday', 'password', 'password_confirmation', 'privacy_policy', 'termsconditions', 'g-recaptcha-response']),
            'kind_of_member' => 'member',
        ]);
        $user = User::sole();
        $this->assertEquals($body['birthday'], $user->birthday->format('d-m-Y'));
        $this->assertNotNull($user->pending_user);
    }

    #[Test]
    public function a_user_should_not_be_created_without_the_required_data_fields()
    {
        $this->post('/signup')
            ->assertRedirect()
            ->assertInvalid([
                'email' => 'Field email is required',
                'firstname' => 'Field firstname is required',
                'lastname' => 'Field lastname is required',
                'street' => 'Field street is required',
                'house_number' => 'Field house number is required',
                'city' => 'Field city is required',
                'phone_number' => 'Field phone number is required',
                'emergency_number' => 'Field emergency number is required',
                'emergency_street' => 'Field emergency street is required',
                'emergency_zipcode' => 'Field emergency zipcode is required',
                'emergency_country' => 'Field emergency country is required',
                'birthday' => 'Field birthday is required',
                'iban' => 'Field IBAN is required',
                'g-recaptcha-response' => "'I'm not a robot' validation is required",
                'incasso' => 'The Automatic Collection field must be accepted.',
                'privacy_policy' => 'Field Privacy Policy is required',
                'termsconditions' => 'Field Terms and Conditions is required',
            ]);

        $this->assertCount(22, session('errors'));
        Event::assertNotDispatched(PendingUserCreated::class);
        $this->assertDatabaseEmpty(User::class);
    }
}
