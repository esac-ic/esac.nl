<?php

namespace Tests\Feature\PendingUser;

use App\Events\PendingUserCreated;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class StorePendingUserTest extends \TestCase
{
    use RefreshDatabase;

    public function test_store_pending_member()
    {
        $requestData = [
            'email' => 'initial@esac.nl',
            'firstname' => 'Initial',
            'lastname' => 'Initial',
            'street' => 'Initial',
            'houseNumber' => '1',
            'city' => 'Initial',
            'zipcode' => '1111AA',
            'country' => 'Initial',
            'phonenumber' => '123456789',
            'emergencyNumber' => '123456789',
            'emergencyHouseNumber' => '1',
            'emergencystreet' =>  'Initial',
            'emergencycity' => 'Initial',
            'emergencyzipcode' =>  '1111AA',
            'emergencycountry' => 'Initial',
            'birthDay' => '1990-01-01',
            'IBAN' =>  '123456789',
            'g-recaptcha-response' => 'True',
            'incasso' => 'True',
            'privacy_policy' => 'True',
            'termsconditions' => 'True'
        ];
        
        Event::fake();
        
        $this->assertEquals(0, User::all()->count());
        
        $response = $this->post(route('user.signup'), $requestData);
        
        $response->assertRedirect(route('front-end.signup'));
        Event::assertDispatched(PendingUserCreated::class);
        $this->assertEquals(1, User::all()->count());
        
        $user =  User::first();
        
        $this->assertEquals('initial@esac.nl', $user->email);
        $this->assertEquals('Initial', $user->firstname);
        $this->assertEquals('Initial', $user->lastname);
        $this->assertEquals('Initial', $user->street);
        $this->assertEquals('1', $user->houseNumber);
        $this->assertEquals('Initial', $user->city);
        $this->assertEquals('1111AA', $user->zipcode);
        $this->assertEquals('Initial', $user->country);
        $this->assertEquals('123456789', $user->phonenumber);
        $this->assertEquals('123456789', $user->emergencyNumber);
        $this->assertEquals('1', $user->emergencyHouseNumber);
        $this->assertEquals('Initial', $user->emergencystreet);
        $this->assertEquals('Initial', $user->emergencycity);
        $this->assertEquals('1111AA', $user->emergencyzipcode);
        $this->assertEquals('Initial', $user->emergencycountry);
        $this->assertEquals('123456789', $user->IBAN);
        
        $this->assertEquals(new Carbon('1990-01-01'), $user->birthDay);
        $this->assertEquals(1, $user->incasso);
        
    }
}
