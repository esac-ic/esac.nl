<?php

namespace Tests\Feature\User;

use App\Enums\AdminRole;
use App\Events\OldMemberBecameMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class MakeActiveMemberTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->withRole(AdminRole::Administrator)->create();
    }

    public function test_make_old_member_active_member()
    {
        $user = User::factory()->create();
        $user->membership_ended_on = Carbon::now();
        $user->save();

        $userId = $user->id;

        Event::fake();

        $response = $this->actingAs($this->admin)->post(route('admin.users.membership.store', $user));

        $response->assertRedirect(route('admin.users.show', $user));
        Event::assertDispatched(OldMemberBecameMember::class);

        $this->assertNull(User::find($userId)->membership_ended_on); // use User::find instead of $user to check if the user is properly updated in the db
    }

    public function test_make_active_member_active()
    {
        $user = User::factory()->create();
        $user->membership_ended_on = null;
        $user->save();

        Event::fake();

        $response = $this->actingAs($this->admin)->post(route('admin.users.membership.store', $user));
        $response->assertRedirect(route('admin.old-members.index'));
        Event::assertNotDispatched(OldMemberBecameMember::class);
    }

    public function test_make_member_without_email_active()
    {
        $user = User::factory()->create();
        $user->membership_ended_on = Carbon::now();
        $user->email = null;
        $user->save();

        $userId = $user->id;

        Event::fake();

        $response = $this->actingAs($this->admin)->post(route('admin.users.membership.store', $user));
        $response->assertRedirect(route('admin.old-members.index'));
        Event::assertNotDispatched(OldMemberBecameMember::class);

        $this->assertNotNull(User::find($userId)->membership_ended_on);
    }

    public function test_make_active_member_without_email_active()
    {
        $user = User::factory()->create();
        $user->membership_ended_on = null;
        $user->email = null;
        $user->save();

        Event::fake();

        $response = $this->actingAs($this->admin)->post(route('admin.users.membership.store', $user));
        $response->assertRedirect(route('admin.old-members.index'));
        Event::assertNotDispatched(OldMemberBecameMember::class);
    }
}
