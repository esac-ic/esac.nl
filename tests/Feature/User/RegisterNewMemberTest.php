<?php

namespace Tests\Feature\User;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use TestCase;

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
        session()->start();
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    /** @test */
    public function a_guest_can_register_a_new_user()
    {
        $body = [
            'email' => 'test@esac.nl',
            'firstname' => 'esac',
            'lastname' => 'esac last name',
            'street' => 'stastion street',
            'houseNumber' => 3,
            'city' => 'eindhoven',
            'zipcode' => '5781JH',
            'country' => 'NL',
            'phonenumber' => 28743939,
            'emergencyNumber' => 112,
            'emergencyHouseNumber' => '2a',
            'emergencystreet' => 'nursery',
            'emergencycity' => 'dead',
            'emergencyzipcode' => '3473JP',
            'emergencycountry' => 'NL',
            'birthDay' => Carbon::now()->subCenturies(2)->format('d-m-Y'),
            'IBAN' => "BLNDj838474784848",
            'incasso' => 1,
            'privacy_policy' => 1,
            'termsconditions' => 1,
            'g-recaptcha-response' => 'ikbenvalid',
            '_token' => csrf_token(),
        ];

        $response = $this->post($this->url, $body);

        $response->assertStatus(302);

        $user = \App\User::all()->last();

        $this->assertEquals($body['email'], $user->email);
        $this->assertEquals($body['firstname'], $user->firstname);
        $this->assertEquals($body['lastname'], $user->lastname);
        $this->assertEquals($body['street'], $user->street);
        $this->assertEquals($body['houseNumber'], $user->houseNumber);
        $this->assertEquals($body['city'], $user->city);
        $this->assertEquals($body['zipcode'], $user->zipcode);
        $this->assertEquals($body['country'], $user->country);
        $this->assertEquals($body['phonenumber'], $user->phonenumber);
        $this->assertEquals($body['emergencyNumber'], $user->emergencyNumber);
        $this->assertEquals($body['emergencyHouseNumber'], $user->emergencyHouseNumber);
        $this->assertEquals($body['emergencystreet'], $user->emergencystreet);
        $this->assertEquals($body['emergencycity'], $user->emergencycity);
        $this->assertEquals($body['emergencyzipcode'], $user->emergencyzipcode);
        $this->assertEquals($body['emergencycountry'], $user->emergencycountry);
        $this->assertEquals($body['birthDay'], Carbon::parse($user->birthDay)->format('d-m-Y'));
        $this->assertEquals('member', $user->kind_of_member);
        $this->assertEquals($body['IBAN'], $user->IBAN);
        $this->assertEquals($body['incasso'], $user->incasso);
        $this->assertNotNull($user->pending_user);
    }

    /** @test */
    public function a_user_should_not_be_created_without_the_required_data_fields()
    {
        $body = [
            '_token' => csrf_token(),
        ];

        $response = $this->post($this->url, $body);

        $response->assertStatus(302);

        $errors = session('errors');
        $this->assertCount(21, $errors);

        $this->assertEquals("Field email is required", $errors->get('email')[0]);
        $this->assertEquals("Field firstname is required", $errors->get('firstname')[0]);
        $this->assertEquals("Field lastname is required", $errors->get('lastname')[0]);
        $this->assertEquals("Field street is required", $errors->get('street')[0]);
        $this->assertEquals("Field house number is required", $errors->get('houseNumber')[0]);
        $this->assertEquals("Field city is required", $errors->get('city')[0]);
        $this->assertEquals("Field phonenumber is required", $errors->get('phonenumber')[0]);
        $this->assertEquals("Field emergency number is required", $errors->get('emergencyNumber')[0]);
        $this->assertEquals("Field emergencystreet is required", $errors->get('emergencystreet')[0]);
        $this->assertEquals("Field emergencyzipcode is required", $errors->get('emergencyzipcode')[0]);
        $this->assertEquals("Field emergencycountry is required", $errors->get('emergencycountry')[0]);
        $this->assertEquals("Field birth day is required", $errors->get('birthDay')[0]);
        $this->assertEquals("Field i b a n is required", $errors->get('IBAN')[0]);
        $this->assertEquals("'I'm not a robot' validation is required", $errors->get('g-recaptcha-response')[0]);
        $this->assertEquals("Field Automatic Collection is required", $errors->get('incasso')[0]);
        $this->assertEquals("Field Privacy Policy is required", $errors->get('privacy_policy')[0]);
        $this->assertEquals("Field Terms and Conditions is required", $errors->get('termsconditions')[0]);
    }
}
