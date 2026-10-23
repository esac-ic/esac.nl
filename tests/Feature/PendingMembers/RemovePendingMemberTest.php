<?php

namespace Tests\Feature\PendingMembers;

use App\Enums\AdminRole;
use App\Events\SignUpRejected;
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

        Event::fake();

        $response = $this->actingAs($this->admin)->delete(route('admin.pending-members.destroy', $user));

        $response->assertRedirect(route('admin.pending-members.index'));
        Event::assertDispatched(SignUpRejected::class);

        $this->assertNull(User::find($userId));
    }
}
