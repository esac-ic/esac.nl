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
        $response = $this->actingAs($this->member)->patch(
            route('users.update', $this->member->id),
            $this->updateData()
        );
        
        $newMember = User::find($this->member->id);

        $this->assertEquals(null, $newMember->treasurer_pending);
    }
    
    public function test_iban_changes()
    {
        $response = $this->actingAs($this->member)->patch(
            route('users.update', $this->member->id),
            $this->updateData(['IBAN' => 'BE71096123456769'])
        );
        
        $newMember = User::find($this->member->id);

        $this->assertEquals(["IBAN"], json_decode($newMember->treasurer_pending));
    }
    
    public function test_bic_changes()
    {        
        $response = $this->actingAs($this->member)->patch(
            route('users.update', $this->member->id),
            $this->updateData(['BIC' => 'BKKPASLCWJ2'])
        );
        
        $newMember = User::find($this->member->id);

        $this->assertEquals(["BIC"], json_decode($newMember->treasurer_pending));
    }
    
    public function test_incasso_changes()
    {
        $response = $this->actingAs($this->member)->patch(
            route('users.update', $this->member->id),
            $this->updateData([], ['incasso'])
        );
        
        $newMember = User::find($this->member->id);

        $this->assertEquals(["incasso"], json_decode($newMember->treasurer_pending));
    }

    public function test_all_changes()
    {       
        $response = $this->actingAs($this->member)->patch(
            route('users.update', $this->member->id),
            $this->updateData(['IBAN' => 'BE71096123456769', 'BIC' => 'BKKPASLCWJ2'], ['incasso'])
        );
        
        $newMember = User::find($this->member->id);

        $this->assertEquals(["IBAN","BIC","incasso"], json_decode($newMember->treasurer_pending));
    }

    public function test_reasons_from_separate_updates_are_kept()
    {
        $this->actingAs($this->member)->patch(
            route('users.update', $this->member->id),
            $this->updateData(['IBAN' => 'BE71096123456769'])
        );
        $this->actingAs($this->member)->patch(
            route('users.update', $this->member->id),
            $this->updateData(['IBAN' => 'BE71096123456769'], ['incasso'])
        );

        $newMember = User::find($this->member->id);

        $this->assertEquals(["IBAN","incasso"], json_decode($newMember->treasurer_pending));
    }

    public function test_repeated_reason_is_stored_as_list()
    {
        $this->actingAs($this->member)->patch(
            route('users.update', $this->member->id),
            $this->updateData(['IBAN' => 'BE71096123456769'])
        );
        // Changing the IBAN twice
        $this->actingAs($this->member)->patch(
            route('users.update', $this->member->id),
            $this->updateData(['IBAN' => 'NL02ABNA0123456789'], ['incasso'])
        );
        $this->actingAs($this->member)->patch(
            route('users.update', $this->member->id),
            $this->updateData(['BIC' => 'BKKPASLCWJ2'], ['incasso'])
        );

        $newMember = User::find($this->member->id);

        $this->assertEquals(["IBAN","incasso","BIC"], json_decode($newMember->treasurer_pending));
    }

    /**
     * The update request for $this->member with its initial values, with $changes applied and $without left out (incasso = false doesnt work, you need to leave it out)
     */
    private function updateData(array $changes = [], array $without = []): array
    {
        $data = array_merge([
            'email' => $this->member->email,
            'firstname' => 'Initial',
            'lastname' => 'Initial',
            'password' => '',
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
            'IBAN' =>  'NL02ABNA0123456789',
            'incasso' => true,
            'kind_of_member' => $this->member->kind_of_member,
        ], $changes);

        return array_diff_key($data, array_flip($without));
    }
}
