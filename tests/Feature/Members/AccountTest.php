<?php

namespace Tests\Feature\Members;

use App\Events\MemberEmailChanged;
use App\Events\MemberTypeChanged;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    /** The member details every test submits. */
    private const DETAILS = [
        'street' => 'Second',
        'house_number' => '2',
        'city' => 'Second',
        'zipcode' => '2222AA',
        'country' => 'DE',
        'phone_number' => '234567891',
        'emergency_number' => '234567891',
        'emergency_house_number' => '2',
        'emergency_street' => 'Second',
        'emergency_city' => 'Second',
        'emergency_zipcode' => '2222AA',
        'emergency_country' => 'DE',
        'birthday' => '1990-02-02',
        'iban' => '234567891',
    ];

    private User $member;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();

        $this->member = User::factory()->member()->create(['email' => 'initial@esac.nl']);
    }

    #[Test]
    public function a_member_can_update_their_own_details()
    {
        $this->actingAs($this->member)->patch(route('account.update'), self::DETAILS)
            ->assertRedirect(route('account.show'));

        Event::assertNotDispatched(MemberTypeChanged::class);
        $this->assertUpdated(self::DETAILS);
    }

    #[Test]
    public function a_member_can_not_change_the_admin_only_fields()
    {
        $forbidden = Arr::only($this->member->attributesToArray(), ['email', 'first_name', 'preposition', 'last_name', 'member_type', 'remark']);

        $this->actingAs($this->member)->patch(route('account.update'), self::DETAILS + array_fill_keys(array_keys($forbidden), 'Second'))
            ->assertRedirect(route('account.show'));

        Event::assertNotDispatched(MemberTypeChanged::class);
        $this->assertUpdated($forbidden + self::DETAILS);
    }

    #[Test]
    public function a_member_can_change_their_email_address_with_their_current_password()
    {
        $this->actingAs($this->member)->put(route('account.login.update'), ['email' => 'second@esac.nl', 'current_password' => 'password'])
            ->assertRedirect(route('account.show'));

        Event::assertDispatched(MemberEmailChanged::class);
        $this->assertSame('second@esac.nl', $this->member->fresh()->email);
        $this->assertSame($this->member->password, $this->member->fresh()->password);
    }

    #[Test]
    public function a_member_can_change_their_password_with_their_current_password()
    {
        $this->actingAs($this->member)->put(route('account.login.update'), [
            'email' => 'initial@esac.nl',
            'password' => 'a new password',
            'password_confirmation' => 'a new password',
            'current_password' => 'password',
        ])->assertRedirect(route('account.show'));

        $this->assertTrue(Hash::check('a new password', $this->member->fresh()->password));
    }

    #[Test]
    public function the_email_address_and_password_take_the_current_password()
    {
        $this->actingAs($this->member)->put(route('account.login.update'), [
            'email' => 'second@esac.nl',
            'password' => 'a new password',
            'password_confirmation' => 'a new password',
            'current_password' => 'wrong',
        ])->assertInvalid('current_password');

        $member = $this->member->fresh();
        $this->assertSame('initial@esac.nl', $member->email);
        $this->assertSame($this->member->password, $member->password);
    }

    private function assertUpdated(array $data): void
    {
        $user = $this->member->fresh();

        $this->assertSame('1990-02-02', $user->birthday->toDateString());
        $this->assertSame($this->member->password, $user->password);
        $expected = Arr::except($data, ['birthday']);
        $this->assertEquals($expected, Arr::only($user->attributesToArray(), array_keys($expected)));
    }
}
