<?php

namespace Tests\Feature\User;

use App\Enums\Role;
use App\Events\MemberKindChanged;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Mockery\MockInterface;
use Tests\TestCase;

class UpdateUserTest extends TestCase
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
            'kind_of_member' => 'Initial',

        ]);
        $this->admin->roles()->attach(Role::Administrator->value);

        $this->member = User::factory()->member()->create([
            'email' => 'Initial', // email should change
        ]);
    }

    public function test_admin_updates_self_email_unchanged()
    {
        $updateData = [
            'email' => $this->admin->email,
            'firstname' => 'New',
            'lastname' => 'Second',
            'password' => '', // password doesn't change, but updating the user errors when password is not present in the request
            'street' => 'Second',
            'house_number' => '2',
            'city' => 'Second',
            'zipcode' => '2222AA',
            'country' => 'Second',
            'phone_number' => '234567891',
            'emergency_number' => '234567891',
            'emergency_house_number' => '2',
            'emergency_street' => 'Second',
            'emergency_city' => 'Second',
            'emergency_zipcode' => '2222AA',
            'emergency_country' => 'Second',
            'birthday' => '1990-02-02',
            'iban' => '234567891',
            'kind_of_member' => $this->admin->kind_of_member,
        ];
        $oldEmail = $this->admin->email;

        Event::fake(); // make sure event listeners are not called

        $mock = $this->mock(Mailman::class, function (MockInterface $mock) {
            $mock->shouldNotReceive('updateUserEmailFormAllMailList');
        });

        $response = $this->actingAs($this->admin)->patch('/users/'.$this->admin->id, $updateData);

        $response->assertRedirect('/users/'.$this->admin->id.'?back=false');

        Event::assertNotDispatched(MemberKindChanged::class);

        $newAdmin = User::find($this->admin->id);

        $this->assertEquals($newAdmin->email, $updateData['email']);
        $this->assertEquals($newAdmin->email, $oldEmail);

        $this->assertEquals($newAdmin->firstname, $updateData['firstname']);
        $this->assertEquals($newAdmin->lastname, $updateData['lastname']);
        $this->assertEquals($newAdmin->street, $updateData['street']);
        $this->assertEquals($newAdmin->house_number, $updateData['house_number']);
        $this->assertEquals($newAdmin->city, $updateData['city']);
        $this->assertEquals($newAdmin->zipcode, $updateData['zipcode']);
        $this->assertEquals($newAdmin->country, $updateData['country']);
        $this->assertEquals($newAdmin->phone_number, $updateData['phone_number']);
        $this->assertEquals($newAdmin->emergency_number, $updateData['emergency_number']);
        $this->assertEquals($newAdmin->emergency_house_number, $updateData['emergency_house_number']);
        $this->assertEquals($newAdmin->emergency_street, $updateData['emergency_street']);
        $this->assertEquals($newAdmin->emergency_city, $updateData['emergency_city']);
        $this->assertEquals($newAdmin->emergency_zipcode, $updateData['emergency_zipcode']);
        $this->assertEquals($newAdmin->emergency_country, $updateData['emergency_country']);
        $this->assertEquals($newAdmin->birthday, new Carbon($updateData['birthday']));
        $this->assertEquals($newAdmin->iban, $updateData['iban']);
        $this->assertEquals($newAdmin->kind_of_member, $updateData['kind_of_member']);
    }

    public function test_admin_updates_self_email_changed()
    {
        $updateData = [
            'email' => 'Second@esac.nl',
            'firstname' => 'New',
            'lastname' => 'Second',
            'password' => '', // password doesn't change, but updating the user errors when password is not present in the request
            'street' => 'Second',
            'house_number' => '2',
            'city' => 'Second',
            'zipcode' => '2222AA',
            'country' => 'Second',
            'phone_number' => '234567891',
            'emergency_number' => '234567891',
            'emergency_house_number' => '2',
            'emergency_street' => 'Second',
            'emergency_city' => 'Second',
            'emergency_zipcode' => '2222AA',
            'emergency_country' => 'Second',
            'birthday' => '1990-02-02',
            'iban' => '234567891',
            'kind_of_member' => $this->admin->kind_of_member,
        ];

        Event::fake(); // make sure event listeners are not called

        $mock = $this->mock(Mailman::class, function (MockInterface $mock) {
            $mock->shouldReceive('updateUserEmailForAllMailList')->once();
        });

        $response = $this->actingAs($this->admin)->patch('/users/'.$this->admin->id, $updateData);

        $response->assertRedirect('/users/'.$this->admin->id.'?back=false');

        Event::assertNotDispatched(MemberKindChanged::class);

        $newAdmin = User::find($this->admin->id);

        $this->assertEquals($newAdmin->email, $updateData['email']);
        $this->assertEquals($newAdmin->firstname, $updateData['firstname']);
        $this->assertEquals($newAdmin->lastname, $updateData['lastname']);
        $this->assertEquals($newAdmin->street, $updateData['street']);
        $this->assertEquals($newAdmin->house_number, $updateData['house_number']);
        $this->assertEquals($newAdmin->city, $updateData['city']);
        $this->assertEquals($newAdmin->zipcode, $updateData['zipcode']);
        $this->assertEquals($newAdmin->country, $updateData['country']);
        $this->assertEquals($newAdmin->phone_number, $updateData['phone_number']);
        $this->assertEquals($newAdmin->emergency_number, $updateData['emergency_number']);
        $this->assertEquals($newAdmin->emergency_house_number, $updateData['emergency_house_number']);
        $this->assertEquals($newAdmin->emergency_street, $updateData['emergency_street']);
        $this->assertEquals($newAdmin->emergency_city, $updateData['emergency_city']);
        $this->assertEquals($newAdmin->emergency_zipcode, $updateData['emergency_zipcode']);
        $this->assertEquals($newAdmin->emergency_country, $updateData['emergency_country']);
        $this->assertEquals($newAdmin->birthday, new Carbon($updateData['birthday']));
        $this->assertEquals($newAdmin->iban, $updateData['iban']);
    }

    public function test_admin_updates_other_member()
    {
        $updateData = [
            'email' => 'Second@esac.nl',
            'firstname' => 'New',
            'lastname' => 'Second',
            'password' => '', // password doesn't change, but updating the user errors when password is not present in the request
            'street' => 'Second',
            'house_number' => '2',
            'city' => 'Second',
            'zipcode' => '2222AA',
            'country' => 'Second',
            'phone_number' => '234567891',
            'emergency_number' => '234567891',
            'emergency_house_number' => '2',
            'emergency_street' => 'Second',
            'emergency_city' => 'Second',
            'emergency_zipcode' => '2222AA',
            'emergency_country' => 'Second',
            'birthday' => '1990-02-02',
            'iban' => '234567891',
            'kind_of_member' => 'reunist',
        ];

        Event::fake(); // make sure event listeners are not called
        $mock = $this->mock(Mailman::class, function (MockInterface $mock) {
            $mock->shouldReceive('updateUserEmailForAllMailList');
        });

        $this->assertNotEquals($this->member->kind_of_member, $updateData['kind_of_member']); // sanity check that we update the member type as well

        $response = $this->actingAs($this->admin)->patch('/users/'.$this->member->id, $updateData);

        $response->assertRedirect('/users');

        Event::assertDispatched(MemberKindChanged::class);

        $newMember = User::find($this->member->id);

        $this->assertEquals($newMember->email, $updateData['email']);
        $this->assertEquals($newMember->firstname, $updateData['firstname']);
        $this->assertEquals($newMember->lastname, $updateData['lastname']);
        $this->assertEquals($newMember->street, $updateData['street']);
        $this->assertEquals($newMember->house_number, $updateData['house_number']);
        $this->assertEquals($newMember->city, $updateData['city']);
        $this->assertEquals($newMember->zipcode, $updateData['zipcode']);
        $this->assertEquals($newMember->country, $updateData['country']);
        $this->assertEquals($newMember->phone_number, $updateData['phone_number']);
        $this->assertEquals($newMember->emergency_number, $updateData['emergency_number']);
        $this->assertEquals($newMember->emergency_house_number, $updateData['emergency_house_number']);
        $this->assertEquals($newMember->emergency_street, $updateData['emergency_street']);
        $this->assertEquals($newMember->emergency_city, $updateData['emergency_city']);
        $this->assertEquals($newMember->emergency_zipcode, $updateData['emergency_zipcode']);
        $this->assertEquals($newMember->emergency_country, $updateData['emergency_country']);
        $this->assertEquals($newMember->birthday, new Carbon($updateData['birthday']));
        $this->assertEquals($newMember->iban, $updateData['iban']);
        $this->assertSame($this->member->password, $newMember->password);
    }

    public function test_a_new_password_must_be_confirmed_and_is_hashed()
    {
        $data = [...$this->admin->only(['email', 'firstname', 'lastname', 'street', 'house_number', 'city', 'zipcode', 'country', 'phone_number', 'emergency_number', 'emergency_house_number', 'emergency_street', 'emergency_city', 'emergency_zipcode', 'emergency_country', 'iban', 'kind_of_member']), 'birthday' => '1990-02-02', 'password' => 'new-password'];

        $this->actingAs($this->admin)->patch(route('users.update', $this->admin), $data)->assertSessionHasErrors('password');

        $this->actingAs($this->admin)->patch(route('users.update', $this->admin), [...$data, 'password_confirmation' => 'new-password'])->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('new-password', $this->admin->fresh()->password));
    }

    public function test_member_updates_self()
    {
        $updateData = [
            'email' => 'Second@esac.nl',
            'firstname' => $this->member->firstname,
            'preposition' => $this->member->preposition,
            'lastname' => $this->member->lastname,
            'password' => '', // password doesn't change, but updating the user errors when password is not present in the request
            'street' => 'Second',
            'house_number' => '2',
            'city' => 'Second',
            'zipcode' => '2222AA',
            'country' => 'Second',
            'phone_number' => '234567891',
            'emergency_number' => '234567891',
            'emergency_house_number' => '2',
            'emergency_street' => 'Second',
            'emergency_city' => 'Second',
            'emergency_zipcode' => '2222AA',
            'emergency_country' => 'Second',
            'birthday' => '1990-02-02',
            'iban' => '234567891',
            'kind_of_member' => $this->member->kind_of_member,
            'remark' => $this->member->remark,
        ];

        Event::fake(); // make sure event listeners are not called

        $mock = $this->mock(Mailman::class, function (MockInterface $mock) {
            $mock->shouldReceive('updateUserEmailForAllMailList')->once();
        });

        $response = $this->actingAs($this->member)->patch('/users/'.$this->member->id, $updateData);

        $response->assertRedirect('/users/'.$this->member->id.'?back=false');

        Event::assertNotDispatched(MemberKindChanged::class);

        $newMember = User::find($this->member->id);

        $this->assertEquals($newMember->email, $updateData['email']);
        $this->assertEquals($newMember->firstname, $updateData['firstname']);
        $this->assertEquals($newMember->preposition, $updateData['preposition']);
        $this->assertEquals($newMember->lastname, $updateData['lastname']);
        $this->assertEquals($newMember->street, $updateData['street']);
        $this->assertEquals($newMember->house_number, $updateData['house_number']);
        $this->assertEquals($newMember->city, $updateData['city']);
        $this->assertEquals($newMember->zipcode, $updateData['zipcode']);
        $this->assertEquals($newMember->country, $updateData['country']);
        $this->assertEquals($newMember->phone_number, $updateData['phone_number']);
        $this->assertEquals($newMember->emergency_number, $updateData['emergency_number']);
        $this->assertEquals($newMember->emergency_house_number, $updateData['emergency_house_number']);
        $this->assertEquals($newMember->emergency_street, $updateData['emergency_street']);
        $this->assertEquals($newMember->emergency_city, $updateData['emergency_city']);
        $this->assertEquals($newMember->emergency_zipcode, $updateData['emergency_zipcode']);
        $this->assertEquals($newMember->emergency_country, $updateData['emergency_country']);
        $this->assertEquals($newMember->birthday, new Carbon($updateData['birthday']));
        $this->assertEquals($newMember->iban, $updateData['iban']);
        $this->assertEquals($newMember->kind_of_member, $updateData['kind_of_member']);
        $this->assertEquals($newMember->remark, $updateData['remark']);
    }

    public function test_member_cannot_update_forbidden_fields()
    {
        $updateData = [
            'email' => 'Second@esac.nl',
            'firstname' => 'Second',
            'preposition' => 'Second',
            'lastname' => 'Second',
            'password' => '', // password doesn't change, but updating the user errors when password is not present in the request
            'street' => 'Second',
            'house_number' => '2',
            'city' => 'Second',
            'zipcode' => '2222AA',
            'country' => 'Second',
            'phone_number' => '234567891',
            'emergency_number' => '234567891',
            'emergency_house_number' => '2',
            'emergency_street' => 'Second',
            'emergency_city' => 'Second',
            'emergency_zipcode' => '2222AA',
            'emergency_country' => 'Second',
            'birthday' => '1990-02-02',
            'iban' => '234567891',
            'kind_of_member' => 'Second',
            'remark' => 'Second',
        ];

        $oldFirstName = $this->member->firstname;
        $oldLastName = $this->member->lastname;
        $oldPreposition = $this->member->preposition;
        $oldKindOfMember = $this->member->kind_of_member;
        $oldRemark = $this->member->remark;

        Event::fake(); // make sure event listeners are not called

        $mock = $this->mock(Mailman::class, function (MockInterface $mock) {
            $mock->shouldReceive('updateUserEmailForAllMailList')->once();
        });

        $response = $this->actingAs($this->member)->patch('/users/'.$this->member->id, $updateData);

        $response->assertRedirect('/users/'.$this->member->id.'?back=false');

        Event::assertNotDispatched(MemberKindChanged::class);

        $newMember = User::find($this->member->id);

        $this->assertEquals($newMember->firstname, $oldFirstName);
        $this->assertEquals($newMember->preposition, $oldPreposition);
        $this->assertEquals($newMember->lastname, $oldLastName);
        $this->assertEquals($newMember->kind_of_member, $oldKindOfMember);
        $this->assertEquals($newMember->remark, $oldRemark);

        $this->assertEquals($newMember->email, $updateData['email']);
        $this->assertEquals($newMember->street, $updateData['street']);
        $this->assertEquals($newMember->house_number, $updateData['house_number']);
        $this->assertEquals($newMember->city, $updateData['city']);
        $this->assertEquals($newMember->zipcode, $updateData['zipcode']);
        $this->assertEquals($newMember->country, $updateData['country']);
        $this->assertEquals($newMember->phone_number, $updateData['phone_number']);
        $this->assertEquals($newMember->emergency_number, $updateData['emergency_number']);
        $this->assertEquals($newMember->emergency_house_number, $updateData['emergency_house_number']);
        $this->assertEquals($newMember->emergency_street, $updateData['emergency_street']);
        $this->assertEquals($newMember->emergency_city, $updateData['emergency_city']);
        $this->assertEquals($newMember->emergency_zipcode, $updateData['emergency_zipcode']);
        $this->assertEquals($newMember->emergency_country, $updateData['emergency_country']);
        $this->assertEquals($newMember->birthday, new Carbon($updateData['birthday']));
        $this->assertEquals($newMember->iban, $updateData['iban']);
    }

    public function test_member_cannot_update_others()
    {
        $updateData = [
            'email' => 'Second@esac.nl',
            'firstname' => $this->member->firstname,
            'preposition' => $this->member->preposition,
            'lastname' => $this->member->lastname,
            'password' => '', // password doesn't change, but updating the user errors when password is not present in the request
            'street' => 'Second',
            'house_number' => '2',
            'city' => 'Second',
            'zipcode' => '2222AA',
            'country' => 'Second',
            'phone_number' => '234567891',
            'emergency_number' => '234567891',
            'emergency_house_number' => '2',
            'emergency_street' => 'Second',
            'emergency_city' => 'Second',
            'emergency_zipcode' => '2222AA',
            'emergency_country' => 'Second',
            'birthday' => '1990-02-02',
            'iban' => '234567891',
            'kind_of_member' => $this->member->kind_of_member,
            'remark' => $this->member->remarks,
        ];

        Event::fake(); // make sure event listeners are not called

        $oldAdminAttributes = User::find($this->admin->id)->getAttributes();

        $mock = $this->mock(Mailman::class, function (MockInterface $mock) {
            $mock->shouldNotReceive('updateUserEmailFormAllMailList');
        });

        $this->assertFalse($this->member->hasBackendRights()); // double check that the member doesn't have rights

        $response = $this->actingAs($this->member)->patch('/users/'.$this->admin->id, $updateData);

        $response->assertStatus(403);

        Event::assertNotDispatched(MemberKindChanged::class);
        $this->assertEquals(User::find($this->admin->id)->getAttributes(), $oldAdminAttributes); // assert the admin model is unchanged
    }
}
