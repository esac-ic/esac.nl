<?php

namespace Tests\Feature\PendingMembers;

use App\Enums\AdminRole;
use App\Enums\MemberLogType;
use App\Events\SignUpRejected;
use App\Models\MemberLogEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RemovePendingMemberTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->withRole(AdminRole::Administrator)->create();
    }

    #[Test]
    public function a_pending_member_can_be_removed()
    {
        $user = User::factory()->pending()->create();
        $userId = $user->id;
        $logEntry = MemberLogEntry::create(['type' => MemberLogType::MemberSignedUp, 'details' => 'Signed up']);
        $logEntry->user()->associate($user)->save();

        Event::fake();

        $response = $this->actingAs($this->admin)->delete(route('admin.pending-members.destroy', $user));

        $response->assertRedirect(route('admin.pending-members.index'));
        Event::assertDispatched(SignUpRejected::class);

        $this->assertDatabaseMissing(User::class, ['id' => $userId]);
        $this->assertNull($logEntry->fresh()->user_id);
    }

    #[Test]
    public function only_a_pending_member_can_be_removed()
    {
        $this->actingAs($this->admin)->delete(route('admin.pending-members.destroy', User::factory()->create()))->assertNotFound();
    }
}
