<?php

namespace Tests\Feature\User;

use App\Enums\Role;
use App\Events\MemberKindChanged;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    /** The member details every test submits; the password stays unchanged when it's empty. */
    private const DETAILS = [
        'password' => '',
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
    ];

    private User $admin;

    private User $member;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();

        $this->admin = User::factory()->member()->withRole(Role::Administrator)->create(['email' => 'initial@esac.nl']);
        $this->member = User::factory()->member()->create(['email' => 'Initial']);
    }

    public function test_admin_updates_self_email_unchanged()
    {
        $this->mock(Mailman::class)->shouldNotReceive('updateUserEmailForAllMailList');
        $data = self::DETAILS + ['email' => $this->admin->email, 'firstname' => 'New', 'lastname' => 'Second', 'kind_of_member' => $this->admin->kind_of_member];

        $this->actingAs($this->admin)->patch(route('users.update', $this->admin), $data)
            ->assertRedirect(route('users.show', [$this->admin, 'back' => 'false']));

        Event::assertNotDispatched(MemberKindChanged::class);
        $this->assertUpdated($this->admin, $data);
    }

    public function test_admin_updates_self_email_changed()
    {
        $this->mock(Mailman::class)->shouldReceive('updateUserEmailForAllMailList')->once();
        $data = self::DETAILS + ['email' => 'Second@esac.nl', 'firstname' => 'New', 'lastname' => 'Second', 'kind_of_member' => $this->admin->kind_of_member];

        $this->actingAs($this->admin)->patch(route('users.update', $this->admin), $data)
            ->assertRedirect(route('users.show', [$this->admin, 'back' => 'false']));

        Event::assertNotDispatched(MemberKindChanged::class);
        $this->assertUpdated($this->admin, $data);
    }

    public function test_admin_updates_other_member()
    {
        $this->mock(Mailman::class)->shouldReceive('updateUserEmailForAllMailList');
        $data = self::DETAILS + ['email' => 'Second@esac.nl', 'firstname' => 'New', 'lastname' => 'Second', 'kind_of_member' => 'reunist'];

        $this->actingAs($this->admin)->patch(route('users.update', $this->member), $data)
            ->assertRedirect(route('users.index'));

        Event::assertDispatched(MemberKindChanged::class);
        $this->assertUpdated($this->member, $data);
        $this->assertSame($this->member->password, $this->member->fresh()->password);
    }

    public function test_a_new_password_must_be_confirmed_and_is_hashed()
    {
        $data = ['password' => 'new-password'] + self::DETAILS + ['email' => $this->admin->email, 'firstname' => 'New', 'lastname' => 'Second', 'kind_of_member' => $this->admin->kind_of_member];

        $this->actingAs($this->admin)->patch(route('users.update', $this->admin), $data)->assertInvalid('password');

        $this->actingAs($this->admin)->patch(route('users.update', $this->admin), [...$data, 'password_confirmation' => 'new-password'])->assertValid();
        $this->assertTrue(Hash::check('new-password', $this->admin->fresh()->password));
    }

    public function test_member_updates_self()
    {
        $this->mock(Mailman::class)->shouldReceive('updateUserEmailForAllMailList')->once();
        $data = self::DETAILS + ['email' => 'Second@esac.nl']
            + $this->member->only(['firstname', 'preposition', 'lastname', 'kind_of_member', 'remark']);

        $this->actingAs($this->member)->patch(route('users.update', $this->member), $data)
            ->assertRedirect(route('users.show', [$this->member, 'back' => 'false']));

        Event::assertNotDispatched(MemberKindChanged::class);
        $this->assertUpdated($this->member, $data);
    }

    public function test_member_cannot_update_forbidden_fields()
    {
        $this->mock(Mailman::class)->shouldReceive('updateUserEmailForAllMailList')->once();
        $forbidden = $this->member->only(['firstname', 'preposition', 'lastname', 'kind_of_member', 'remark']);
        $data = self::DETAILS + ['email' => 'Second@esac.nl'] + array_fill_keys(array_keys($forbidden), 'Second');

        $this->actingAs($this->member)->patch(route('users.update', $this->member), $data)
            ->assertRedirect(route('users.show', [$this->member, 'back' => 'false']));

        Event::assertNotDispatched(MemberKindChanged::class);
        $this->assertUpdated($this->member, $forbidden + $data);
    }

    public function test_member_cannot_update_others()
    {
        $this->mock(Mailman::class)->shouldNotReceive('updateUserEmailForAllMailList');
        $before = $this->admin->fresh()->getAttributes();

        $this->actingAs($this->member)->patch(route('users.update', $this->admin), self::DETAILS + ['email' => 'Second@esac.nl'])
            ->assertForbidden();

        Event::assertNotDispatched(MemberKindChanged::class);
        $this->assertSame($before, $this->admin->fresh()->getAttributes());
    }

    private function assertUpdated(User $user, array $data): void
    {
        $user = $user->fresh();

        $this->assertSame('1990-02-02', $user->birthday->toDateString());
        $expected = Arr::except($data, ['password', 'birthday']);
        $this->assertEquals($expected, $user->only(array_keys($expected)));
    }
}
