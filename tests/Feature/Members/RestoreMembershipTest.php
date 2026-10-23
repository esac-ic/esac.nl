<?php

namespace Tests\Feature\Members;

use App\Enums\AdminRole;
use App\Events\MembershipRestored;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RestoreMembershipTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->withRole(AdminRole::Administrator)->create();
    }

    #[Test]
    public function a_former_member_can_be_restored()
    {
        $user = User::factory()->create();
        $user->membership_ended_on = Carbon::now();
        $user->save();

        $userId = $user->id;

        Event::fake();

        $response = $this->actingAs($this->admin)->post(route('admin.members.membership.store', $user));

        $response->assertRedirect(route('admin.members.show', $user));
        Event::assertDispatched(MembershipRestored::class);

        $this->assertNull(User::find($userId)->membership_ended_on); // use User::find instead of $user to check if the user is properly updated in the db
    }

    public static function membersThatCanNotBeRestored(): array
    {
        return [
            'active member' => [null, 'member@esac.nl'],
            'anonymized former member' => ['2020-01-01', null],
            'active member without an email address' => [null, null],
        ];
    }

    #[Test]
    #[DataProvider('membersThatCanNotBeRestored')]
    public function only_a_former_member_with_an_email_address_can_be_restored(?string $membershipEndedOn, ?string $email)
    {
        $user = User::factory()->create();
        $user->forceFill(['membership_ended_on' => $membershipEndedOn, 'email' => $email])->save();
        Event::fake();

        $this->actingAs($this->admin)->post(route('admin.members.membership.store', $user))->assertNotFound();

        Event::assertNotDispatched(MembershipRestored::class);
    }
}
