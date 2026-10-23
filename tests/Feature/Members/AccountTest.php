<?php

namespace Tests\Feature\Members;

use App\Events\MemberEmailChanged;
use App\Events\MemberTypeChanged;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    /** The member details every test submits; the password stays unchanged when it's empty. */
    private const DETAILS = [
        'password' => '',
        'current_password' => 'password',
        'email' => 'Second@esac.nl',
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
        Event::assertDispatched(MemberEmailChanged::class);
        $this->assertUpdated(self::DETAILS);
    }

    #[Test]
    public function a_member_can_not_change_the_admin_only_fields()
    {
        $forbidden = Arr::only($this->member->attributesToArray(), ['first_name', 'preposition', 'last_name', 'member_type', 'remark']);

        $this->actingAs($this->member)->patch(route('account.update'), self::DETAILS + array_fill_keys(array_keys($forbidden), 'Second'))
            ->assertRedirect(route('account.show'));

        Event::assertNotDispatched(MemberTypeChanged::class);
        $this->assertUpdated($forbidden + self::DETAILS);
    }

    #[Test]
    public function a_new_email_address_takes_the_current_password()
    {
        $this->actingAs($this->member)->patch(route('account.update'), ['current_password' => 'wrong'] + self::DETAILS)
            ->assertInvalid('current_password');

        $this->assertSame('initial@esac.nl', $this->member->fresh()->email);
    }

    private function assertUpdated(array $data): void
    {
        $user = $this->member->fresh();

        $this->assertSame('1990-02-02', $user->birthday->toDateString());
        $this->assertSame($this->member->password, $user->password);
        $expected = Arr::except($data, ['password', 'current_password', 'birthday']);
        $this->assertEquals($expected, Arr::only($user->attributesToArray(), array_keys($expected)));
    }
}
