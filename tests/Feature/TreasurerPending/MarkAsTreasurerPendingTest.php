<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MarkAsTreasurerPendingTest extends \TestCase
{
    use RefreshDatabase;
    
    private User $member;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->member = User::factory()->member()->create([
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
            'IBAN' =>  'NL02ABNA0123456789',
            'kind_of_member' => 'Initial',
            'incasso' => true
        ]);
    }
    
    public function test_no_important_changes()
    {
        $updateData = [
            'email' => $this->member->email,
            'firstname' => 'First',
            'lastname' => 'First',
            'password' => '', //password doesn't change, but the userRepository errors when password is not present in the request
            'street' => 'First',
            'houseNumber' => '4',
            'city' => 'First',
            'zipcode' => '3333AA',
            'country' => 'First',
            'phonenumber' => '123456789',
            'emergencyNumber' => '123456789',
            'emergencyHouseNumber' => '4',
            'emergencystreet' =>  'First',
            'emergencycity' => 'First',
            'emergencyzipcode' =>  '3333AA',
            'emergencycountry' => 'First',
            'birthDay' => '2000-02-02',
            'IBAN' =>  'NL02ABNA0123456789',
            'incasso' => true,
            'kind_of_member' => $this->member->kind_of_member,
        ];
                
        $response = $this->actingAs($this->member)->patch('/users/' . $this->member->id, $updateData);
        
        $newMember = User::find($this->member->id);

        $this->assertEquals(null, $newMember->treasurer_pending);
    }
    
    public function test_iban_changes()
    {
        $updateData = [
            'email' => $this->member->email,
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
            'IBAN' =>  'BE71096123456769',
            'incasso' => true,
            'kind_of_member' => $this->member->kind_of_member,
        ];

        $response = $this->actingAs($this->member)->patch('/users/' . $this->member->id, $updateData);
        
        $newMember = User::find($this->member->id);

        $this->assertEquals('["IBAN"]', $newMember->treasurer_pending);
    }
    
    public function test_bic_changes()
    {
        $updateData = [
            'email' => $this->member->email,
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
            'IBAN' =>  'NL02ABNA0123456789',
            'BIC' =>  'BKKPASLCWJ2',
            'incasso' => true,
            'kind_of_member' => $this->member->kind_of_member,            
        ];
        
        $response = $this->actingAs($this->member)->patch('/users/' . $this->member->id, $updateData);
        
        $newMember = User::find($this->member->id);

        $this->assertEquals('["BIC"]', $newMember->treasurer_pending);
    }
    
    public function test_incasso_changes()
    {
        $updateData = [
            'email' => $this->member->email,
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
            'IBAN' =>  'NL02ABNA0123456789',
            'kind_of_member' => $this->member->kind_of_member,            
        ];
        
        $response = $this->actingAs($this->member)->patch('/users/' . $this->member->id, $updateData);
        
        $newMember = User::find($this->member->id);

        $this->assertEquals('["incasso"]', $newMember->treasurer_pending);
    }

    public function test_all_changes()
    {
        $updateData = [
            'email' => $this->member->email,
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
            'IBAN' =>  'BE71096123456769',
            'BIC' =>  'BKKPASLCWJ2',
            'kind_of_member' => $this->member->kind_of_member,
        ];
        
        $response = $this->actingAs($this->member)->patch('/users/' . $this->member->id, $updateData);
        
        $newMember = User::find($this->member->id);

        $this->assertEquals('["IBAN","BIC","incasso"]', $newMember->treasurer_pending);
    }
}
