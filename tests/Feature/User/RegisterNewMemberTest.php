<?php

namespace Tests\Feature\User;

use App\Events\PendingUserCreated;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterNewMemberTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var string
     */
    private $url = '/signup';

    /**
     * @var
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Http::fake(['www.google.com/recaptcha/*' => Http::response(['success' => true])]);
        session()->start();
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
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
            'birthday' => Carbon::now()->subCenturies(2)->format('d-m-Y'),
            'iban' => "BLNDj838474784848",
            'incasso' => 1,
            'privacy_policy' => 1,
            'termsconditions' => 1,
            'g-recaptcha-response' => 'ikbenvalid',
            '_token' => csrf_token(),
        ];

        $response = $this->post($this->url, $body);

        $response->assertStatus(302);
        Event::assertDispatched(PendingUserCreated::class);

        $user = User::all()->last();

        $this->assertEquals($body['email'], $user->email);
        $this->assertEquals($body['firstname'], $user->firstname);
        $this->assertEquals($body['lastname'], $user->lastname);
        $this->assertEquals($body['street'], $user->street);
        $this->assertEquals($body['house_number'], $user->house_number);
        $this->assertEquals($body['city'], $user->city);
        $this->assertEquals($body['zipcode'], $user->zipcode);
        $this->assertEquals($body['country'], $user->country);
        $this->assertEquals($body['phone_number'], $user->phone_number);
        $this->assertEquals($body['emergency_number'], $user->emergency_number);
        $this->assertEquals($body['emergency_house_number'], $user->emergency_house_number);
        $this->assertEquals($body['emergency_street'], $user->emergency_street);
        $this->assertEquals($body['emergency_city'], $user->emergency_city);
        $this->assertEquals($body['emergency_zipcode'], $user->emergency_zipcode);
        $this->assertEquals($body['emergency_country'], $user->emergency_country);
        $this->assertEquals($body['birthday'], Carbon::parse($user->birthday)->format('d-m-Y'));
        $this->assertEquals('member', $user->kind_of_member);
        $this->assertEquals($body['iban'], $user->iban);
        $this->assertEquals($body['incasso'], $user->incasso);
        $this->assertNotNull($user->pending_user);
    }

    #[Test]
    public function a_user_should_not_be_created_without_the_required_data_fields()
    {
        $body = [
            '_token' => csrf_token(),
        ];

        $response = $this->post($this->url, $body);

        $response->assertStatus(302);
        Event::assertNotDispatched(PendingUserCreated::class);

        $response->assertSessionHasErrors();
        $errors = session('errors');
        $this->assertCount(21, $errors);

        $this->assertEquals("Field email is required", $errors->get('email')[0]);
        $this->assertEquals("Field firstname is required", $errors->get('firstname')[0]);
        $this->assertEquals("Field lastname is required", $errors->get('lastname')[0]);
        $this->assertEquals("Field street is required", $errors->get('street')[0]);
        $this->assertEquals("Field house number is required", $errors->get('house_number')[0]);
        $this->assertEquals("Field city is required", $errors->get('city')[0]);
        $this->assertEquals("Field phone number is required", $errors->get('phone_number')[0]);
        $this->assertEquals("Field emergency number is required", $errors->get('emergency_number')[0]);
        $this->assertEquals("Field emergency street is required", $errors->get('emergency_street')[0]);
        $this->assertEquals("Field emergency zipcode is required", $errors->get('emergency_zipcode')[0]);
        $this->assertEquals("Field emergency country is required", $errors->get('emergency_country')[0]);
        $this->assertEquals("Field birthday is required", $errors->get('birthday')[0]);
        $this->assertEquals("Field IBAN is required", $errors->get('iban')[0]);
        $this->assertEquals("'I'm not a robot' validation is required", $errors->get('g-recaptcha-response')[0]);
        $this->assertEquals("Field Automatic Collection is required", $errors->get('incasso')[0]);
        $this->assertEquals("Field Privacy Policy is required", $errors->get('privacy_policy')[0]);
        $this->assertEquals("Field Terms and Conditions is required", $errors->get('termsconditions')[0]);
    }
}
