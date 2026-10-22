<?php

namespace Tests\Feature\Members;

use App\Enums\AdminRole;
use App\Events\MembershipRestored;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
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

    #[Test]
    public function restoring_an_active_member_changes_nothing()
    {
        $user = User::factory()->create();
        $user->membership_ended_on = null;
        $user->save();

        Event::fake();

        $response = $this->actingAs($this->admin)->post(route('admin.members.membership.store', $user));
        $response->assertRedirect(route('admin.former-members.index'));
        Event::assertNotDispatched(MembershipRestored::class);
    }

    #[Test]
    public function a_former_member_without_an_email_address_can_not_be_restored()
    {
        $user = User::factory()->create();
        $user->membership_ended_on = Carbon::now();
        $user->email = null;
        $user->save();

        $userId = $user->id;

        Event::fake();

        $response = $this->actingAs($this->admin)->post(route('admin.members.membership.store', $user));
        $response->assertRedirect(route('admin.former-members.index'));
        Event::assertNotDispatched(MembershipRestored::class);

        $this->assertNotNull(User::find($userId)->membership_ended_on);
    }

    #[Test]
    public function restoring_an_active_member_without_an_email_address_changes_nothing()
    {
        $user = User::factory()->create();
        $user->membership_ended_on = null;
        $user->email = null;
        $user->save();

        Event::fake();

        $response = $this->actingAs($this->admin)->post(route('admin.members.membership.store', $user));
        $response->assertRedirect(route('admin.former-members.index'));
        Event::assertNotDispatched(MembershipRestored::class);
    }
}
