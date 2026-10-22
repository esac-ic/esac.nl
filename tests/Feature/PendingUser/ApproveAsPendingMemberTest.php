<?php

namespace Tests\Feature\PendingUser;

use App\Enums\AdminRole;
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

        $this->admin = User::factory()->withRole(AdminRole::Administrator)->create();
    }

    public function test_approve_pending_member()
    {
        $user = User::factory()->pending()->create();
        $userId = $user->id;

        Event::fake();

        $response = $this->actingAs($this->admin)->patch(route('admin.pending-users.approve', $user));

        $response->assertRedirect(route('admin.pending-users.index'));
        Event::assertDispatched(PendingUserApproved::class);

        $this->assertNull(User::find($userId)->pending_since);
    }
}
