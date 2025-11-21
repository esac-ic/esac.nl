<?php

namespace Tests\Feature\User;

use App\CustomClasses\MailList\MailListFacade;
use App\Events\MemberTypeChanged;
use App\User;
use DateTime;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Mockery;
use Mockery\MockInterface;

class UpdateUserTest extends \TestCase
{
    use RefreshDatabase;
    
    private $admin;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->member()->create([
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
            'birthday' => '1990-01-01',
            'IBAN' =>  '123456789',
            'kind_of_member' => 'Initial'
        
        ]);
        $this->admin->roles()->attach(Config::get('constants.Administrator'));
    }
    
    public function test_admin_updates_self_email_unchanged()
    {
        $updateData = [
            'email' => $this->admin->email,
            'firstname' => 'New',
            'lastname' => 'Second',
            'password' => '', //password doesn't change, but the userRepository errors when password is not present in the request
            'street' => 'Second',
            'houseNumber' => '2',
            'city' => 'Second',
            'zipcode' => '2222AA',
            'country' => 'Second',
            'phonenumber' => '234567891',
            'emergencyNumber' => '234567891',
            'emergencyHouseNumber' => '2',
            'emergencystreet' =>  'Second',
            'emergencycity' => 'Second',
            'emergencyzipcode' =>  '2222AA',
            'emergencycountry' => 'Second',
            'birthDay' => '1990-02-02',
            'IBAN' =>  '234567891',
            'kind_of_member' => $this->admin->kind_of_member,
        ];
        $oldEmail = $this->admin->email;
        
        Event::fake(); //make sure event listeners are not called
        $mock = $this->mock(MailListFacade::class, function (MockInterface $mock) {
            $mock->shouldNotReceive('updateUserEmailFormAllMailList');
        });
        
        $response = $this->actingAs($this->admin)->patch('/users/' . $this->admin->id, $updateData);
        
//        $response->dump();
        
        $response->assertRedirect('/users/' . $this->admin->id . '?back=false');
        
        Event::assertNotDispatched(MemberTypeChanged::class);
        
        $newAdmin = User::find($this->admin->id);
        
        $this->assertEquals($newAdmin->email, $updateData['email']);
        $this->assertEquals($newAdmin->email, $oldEmail);
        
        $this->assertEquals($newAdmin->firstname, $updateData['firstname']);
        $this->assertEquals($newAdmin->lastname, $updateData['lastname']);
        $this->assertEquals($newAdmin->street, $updateData['street']);
        $this->assertEquals($newAdmin->houseNumber, $updateData['houseNumber']);
        $this->assertEquals($newAdmin->city, $updateData['city']);
        $this->assertEquals($newAdmin->zipcode, $updateData['zipcode']);
        $this->assertEquals($newAdmin->country, $updateData['country']);
        $this->assertEquals($newAdmin->phonenumber, $updateData['phonenumber']);
        $this->assertEquals($newAdmin->emergencyNumber, $updateData['emergencyNumber']);
        $this->assertEquals($newAdmin->emergencyHouseNumber, $updateData['emergencyHouseNumber']);
        $this->assertEquals($newAdmin->emergencystreet, $updateData['emergencystreet']);
        $this->assertEquals($newAdmin->emergencycity, $updateData['emergencycity']);
        $this->assertEquals($newAdmin->emergencyzipcode, $updateData['emergencyzipcode']);
        $this->assertEquals($newAdmin->emergencycountry, $updateData['emergencycountry']);
        $this->assertEquals($newAdmin->birthDay, new Carbon($updateData['birthDay']));
        $this->assertEquals($newAdmin->IBAN, $updateData['IBAN']);
        $this->assertEquals($newAdmin->kind_of_member, $updateData['kind_of_member']);
    }
    
    public function test_admin_updates_self_email_changed()
    {}
    
    public function test_admin_updates_other_member()
    {}
    
    public function test_member_updates_self()
    {}
    
    public function test_member_cannot_update_forbidden_fields()
    {}
    
    public function test_member_cannot_update_others()
    {}
    
    
}
