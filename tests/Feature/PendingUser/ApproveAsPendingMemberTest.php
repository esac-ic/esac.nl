<?php

namespace Tests\Feature\PendingUser;

use App\Enums\Role;
use App\Events\PendingUserApproved;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ApproveAsPendingMemberTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->withRole(Role::Administrator)->create();
    }

    public function test_approve_pending_member()
    {
        $user = User::factory()->pending()->create();
        $userId = $user->id;

        Event::fake();

        $response = $this->actingAs($this->admin)->patch(route('users.approveAsPendingMember', $user));

        $response->assertRedirect(route('users.indexPendingMembers'));
        Event::assertDispatched(PendingUserApproved::class);

        $this->assertNull(User::find($userId)->pending_user);
    }
}
