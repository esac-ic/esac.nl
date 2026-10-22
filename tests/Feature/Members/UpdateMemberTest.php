<?php

namespace Tests\Feature\Members;

use App\Enums\AdminRole;
use App\Events\MemberEmailChanged;
use App\Events\MemberTypeChanged;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateMemberTest extends TestCase
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

        $this->admin = User::factory()->member()->withRole(AdminRole::Administrator)->create(['email' => 'initial@esac.nl']);
        $this->member = User::factory()->member()->create(['email' => 'Initial']);
    }

    #[Test]
    public function an_admin_can_update_their_own_details_without_changing_their_email()
    {
        $data = self::DETAILS + ['email' => $this->admin->email, 'first_name' => 'New', 'last_name' => 'Second', 'member_type' => $this->admin->member_type->value];

        $this->actingAs($this->admin)->patch(route('admin.members.update', $this->admin), $data)
            ->assertRedirect(route('admin.members.show', $this->admin));

        Event::assertNotDispatched(MemberTypeChanged::class);
        Event::assertNotDispatched(MemberEmailChanged::class);
        $this->assertUpdated($this->admin, $data);
    }

    #[Test]
    public function an_admin_can_change_their_own_email()
    {
        $data = self::DETAILS + ['email' => 'Second@esac.nl', 'first_name' => 'New', 'last_name' => 'Second', 'member_type' => $this->admin->member_type->value];

        $this->actingAs($this->admin)->patch(route('admin.members.update', $this->admin), $data)
            ->assertRedirect(route('admin.members.show', $this->admin));

        Event::assertNotDispatched(MemberTypeChanged::class);
        Event::assertDispatched(MemberEmailChanged::class);
        $this->assertUpdated($this->admin, $data);
    }

    #[Test]
    public function an_admin_can_update_another_member()
    {
        $data = self::DETAILS + ['email' => 'Second@esac.nl', 'first_name' => 'New', 'last_name' => 'Second', 'member_type' => 'alumnus'];

        $this->actingAs($this->admin)->patch(route('admin.members.update', $this->member), $data)
            ->assertRedirect(route('admin.members.index'));

        Event::assertDispatched(MemberTypeChanged::class);
        Event::assertDispatched(MemberEmailChanged::class);
        $this->assertUpdated($this->member, $data);
        $this->assertSame($this->member->password, $this->member->fresh()->password);
    }

    #[Test]
    public function a_new_password_must_be_confirmed_and_is_hashed()
    {
        $data = ['password' => 'new-password'] + self::DETAILS + ['email' => $this->admin->email, 'first_name' => 'New', 'last_name' => 'Second', 'member_type' => $this->admin->member_type->value];

        $this->actingAs($this->admin)->patch(route('admin.members.update', $this->admin), $data)->assertInvalid('password');

        $this->actingAs($this->admin)->patch(route('admin.members.update', $this->admin), [...$data, 'password_confirmation' => 'new-password'])->assertValid();
        $this->assertTrue(Hash::check('new-password', $this->admin->fresh()->password));
    }

    #[Test]
    public function a_member_can_update_their_own_details()
    {
        $data = self::DETAILS + ['email' => 'Second@esac.nl']
            + Arr::only($this->member->attributesToArray(), ['first_name', 'preposition', 'last_name', 'member_type', 'remark']);

        $this->actingAs($this->member)->patch(route('admin.members.update', $this->member), $data)
            ->assertRedirect(route('admin.members.show', $this->member));

        Event::assertNotDispatched(MemberTypeChanged::class);
        Event::assertDispatched(MemberEmailChanged::class);
        $this->assertUpdated($this->member, $data);
    }

    #[Test]
    public function a_member_can_not_change_the_admin_only_fields()
    {
        $forbidden = Arr::only($this->member->attributesToArray(), ['first_name', 'preposition', 'last_name', 'member_type', 'remark']);
        $data = self::DETAILS + ['email' => 'Second@esac.nl'] + array_fill_keys(array_keys($forbidden), 'Second');

        $this->actingAs($this->member)->patch(route('admin.members.update', $this->member), $data)
            ->assertRedirect(route('admin.members.show', $this->member));

        Event::assertNotDispatched(MemberTypeChanged::class);
        Event::assertDispatched(MemberEmailChanged::class);
        $this->assertUpdated($this->member, $forbidden + $data);
    }

    #[Test]
    public function a_member_can_not_update_another_member()
    {
        $before = $this->admin->fresh()->getAttributes();

        $this->actingAs($this->member)->patch(route('admin.members.update', $this->admin), self::DETAILS + ['email' => 'Second@esac.nl'])
            ->assertForbidden();

        Event::assertNotDispatched(MemberTypeChanged::class);
        Event::assertNotDispatched(MemberEmailChanged::class);
        $this->assertSame($before, $this->admin->fresh()->getAttributes());
    }

    private function assertUpdated(User $user, array $data): void
    {
        $user = $user->fresh();

        $this->assertSame('1990-02-02', $user->birthday->toDateString());
        $expected = Arr::except($data, ['password', 'birthday']);
        $this->assertEquals($expected, Arr::only($user->attributesToArray(), array_keys($expected)));
    }
}
