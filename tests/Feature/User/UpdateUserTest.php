<?php

namespace Tests\Feature\User;

use App\CustomClasses\MailList\MailListFacade;
use App\Events\MemberKindChanged;
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
    
    private User $admin;
    private User $member;
    
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
        
        $this->member = User::factory()->member()->create([
            'email' => 'Initial', //email should change
        ]);
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
        
        $response->assertRedirect('/users/' . $this->admin->id . '?back=false');
        
        Event::assertNotDispatched(MemberKindChanged::class);
        
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
    {
        $updateData = [
            'email' => 'Second@esac.nl',
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
        
        Event::fake(); //make sure event listeners are not called
        
        $mock = $this->mock(MailListFacade::class, function (MockInterface $mock) {
            $mock->shouldReceive('updateUserEmailForAllMailList')->once();
        });
        
        $response = $this->actingAs($this->admin)->patch('/users/' . $this->admin->id, $updateData);
        
        $response->assertRedirect('/users/' . $this->admin->id . '?back=false');
        
        Event::assertNotDispatched(MemberKindChanged::class);
        
        $newAdmin = User::find($this->admin->id);
        
        $this->assertEquals($newAdmin->email, $updateData['email']);
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
    }
    
    public function test_admin_updates_other_member()
    {
        $updateData = [
            'email' => 'Second@esac.nl',
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
            'kind_of_member' => 'reunist',
        ];
        
        Event::fake(); //make sure event listeners are not called
        $mock = $this->mock(MailListFacade::class, function (MockInterface $mock) {
            $mock->shouldReceive('updateUserEmailForAllMailList');
        });
        
        $this->assertNotEquals($this->member->kind_of_member, $updateData['kind_of_member']); //sanity check that we update the member type as well
        
        $response = $this->actingAs($this->admin)->patch('/users/' . $this->member->id, $updateData);
        
        $response->assertRedirect('/users');
        
        Event::assertDispatched(MemberKindChanged::class);
        
        $newMember = User::find($this->member->id);
        
        $this->assertEquals($newMember->email, $updateData['email']);
        $this->assertEquals($newMember->firstname, $updateData['firstname']);
        $this->assertEquals($newMember->lastname, $updateData['lastname']);
        $this->assertEquals($newMember->street, $updateData['street']);
        $this->assertEquals($newMember->houseNumber, $updateData['houseNumber']);
        $this->assertEquals($newMember->city, $updateData['city']);
        $this->assertEquals($newMember->zipcode, $updateData['zipcode']);
        $this->assertEquals($newMember->country, $updateData['country']);
        $this->assertEquals($newMember->phonenumber, $updateData['phonenumber']);
        $this->assertEquals($newMember->emergencyNumber, $updateData['emergencyNumber']);
        $this->assertEquals($newMember->emergencyHouseNumber, $updateData['emergencyHouseNumber']);
        $this->assertEquals($newMember->emergencystreet, $updateData['emergencystreet']);
        $this->assertEquals($newMember->emergencycity, $updateData['emergencycity']);
        $this->assertEquals($newMember->emergencyzipcode, $updateData['emergencyzipcode']);
        $this->assertEquals($newMember->emergencycountry, $updateData['emergencycountry']);
        $this->assertEquals($newMember->birthDay, new Carbon($updateData['birthDay']));
        $this->assertEquals($newMember->IBAN, $updateData['IBAN']);
    }
    
    public function test_member_updates_self()
    {
        $updateData = [
            'email' => 'Second@esac.nl',
            'firstname' => $this->member->firstname,
            'preposition' => $this->member->preposition,
            'lastname' => $this->member->lastname,
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
            'kind_of_member' => $this->member->kind_of_member,
            'remark' => $this->member->remark,
        ];
        
        Event::fake(); //make sure event listeners are not called
        
        $mock = $this->mock(MailListFacade::class, function (MockInterface $mock) {
            $mock->shouldReceive('updateUserEmailForAllMailList')->once();
        });
        
        $response = $this->actingAs($this->member)->patch('/users/' . $this->member->id, $updateData);
        
        $response->assertRedirect('/users/' . $this->member->id . '?back=false');
        
        Event::assertNotDispatched(MemberKindChanged::class);
        
        $newMember = User::find($this->member->id);
        
        $this->assertEquals($newMember->email, $updateData['email']);
        $this->assertEquals($newMember->firstname, $updateData['firstname']);
        $this->assertEquals($newMember->preposition, $updateData['preposition']);
        $this->assertEquals($newMember->lastname, $updateData['lastname']);
        $this->assertEquals($newMember->street, $updateData['street']);
        $this->assertEquals($newMember->houseNumber, $updateData['houseNumber']);
        $this->assertEquals($newMember->city, $updateData['city']);
        $this->assertEquals($newMember->zipcode, $updateData['zipcode']);
        $this->assertEquals($newMember->country, $updateData['country']);
        $this->assertEquals($newMember->phonenumber, $updateData['phonenumber']);
        $this->assertEquals($newMember->emergencyNumber, $updateData['emergencyNumber']);
        $this->assertEquals($newMember->emergencyHouseNumber, $updateData['emergencyHouseNumber']);
        $this->assertEquals($newMember->emergencystreet, $updateData['emergencystreet']);
        $this->assertEquals($newMember->emergencycity, $updateData['emergencycity']);
        $this->assertEquals($newMember->emergencyzipcode, $updateData['emergencyzipcode']);
        $this->assertEquals($newMember->emergencycountry, $updateData['emergencycountry']);
        $this->assertEquals($newMember->birthDay, new Carbon($updateData['birthDay']));
        $this->assertEquals($newMember->IBAN, $updateData['IBAN']);
        $this->assertEquals($newMember->kind_of_member, $updateData['kind_of_member']);
        $this->assertEquals($newMember->remark,  $updateData['remark']);
    }
    
    public function test_member_cannot_update_forbidden_fields()
    {
        $updateData = [
            'email' => 'Second@esac.nl',
            'firstname' => 'Second',
            'preposition' => 'Second',
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
            'kind_of_member' => 'Second',
            'remark' => 'Second',
        ];
        
        $oldFirstName = $this->member->firstname;
        $oldLastName = $this->member->lastname;
        $oldPreposition = $this->member->preposition;
        $oldKindOfMember = $this->member->kind_of_member;
        $oldRemark = $this->member->remark;
        
        Event::fake(); //make sure event listeners are not called
        
        $mock = $this->mock(MailListFacade::class, function (MockInterface $mock) {
            $mock->shouldReceive('updateUserEmailForAllMailList')->once();
        });
        
        $response = $this->actingAs($this->member)->patch('/users/' . $this->member->id, $updateData);
        
        $response->assertRedirect('/users/' . $this->member->id . '?back=false');
        
        Event::assertNotDispatched(MemberKindChanged::class);
        
        $newMember = User::find($this->member->id);
        
        $this->assertEquals($newMember->firstname, $oldFirstName);
        $this->assertEquals($newMember->preposition, $oldPreposition);
        $this->assertEquals($newMember->lastname, $oldLastName);
        $this->assertEquals($newMember->kind_of_member, $oldKindOfMember);
        $this->assertEquals($newMember->remark,  $oldRemark);
        
        $this->assertEquals($newMember->email, $updateData['email']);
        $this->assertEquals($newMember->street, $updateData['street']);
        $this->assertEquals($newMember->houseNumber, $updateData['houseNumber']);
        $this->assertEquals($newMember->city, $updateData['city']);
        $this->assertEquals($newMember->zipcode, $updateData['zipcode']);
        $this->assertEquals($newMember->country, $updateData['country']);
        $this->assertEquals($newMember->phonenumber, $updateData['phonenumber']);
        $this->assertEquals($newMember->emergencyNumber, $updateData['emergencyNumber']);
        $this->assertEquals($newMember->emergencyHouseNumber, $updateData['emergencyHouseNumber']);
        $this->assertEquals($newMember->emergencystreet, $updateData['emergencystreet']);
        $this->assertEquals($newMember->emergencycity, $updateData['emergencycity']);
        $this->assertEquals($newMember->emergencyzipcode, $updateData['emergencyzipcode']);
        $this->assertEquals($newMember->emergencycountry, $updateData['emergencycountry']);
        $this->assertEquals($newMember->birthDay, new Carbon($updateData['birthDay']));
        $this->assertEquals($newMember->IBAN, $updateData['IBAN']);
    }
    
    public function test_member_cannot_update_others()
    {
        $updateData = [
            'email' => 'Second@esac.nl',
            'firstname' => $this->member->firstname,
            'preposition' => $this->member->preposition,
            'lastname' => $this->member->lastname,
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
            'kind_of_member' => $this->member->kind_of_member,
            'remark' => $this->member->remarks,
        ];
        
        Event::fake(); //make sure event listeners are not called
        
        $oldAdminAttributes = User::find($this->admin->id)->getAttributes();
        
        $mock = $this->mock(MailListFacade::class, function (MockInterface $mock) {
            $mock->shouldNotReceive('updateUserEmailFormAllMailList');
        });
        
        $this->assertFalse($this->member->hasBackendRights()); //double check that the member doesn't have rights
        
        $response = $this->actingAs($this->member)->patch('/users/' . $this->admin->id, $updateData);
        
        $response->assertStatus(403);
        
        Event::assertNotDispatched(MemberKindChanged::class);
        $this->assertEquals(User::find($this->admin->id)->getAttributes(), $oldAdminAttributes); //assert the admin model is unchanged
    }
}
