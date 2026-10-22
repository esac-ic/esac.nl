<?php

namespace Tests\Feature\PendingUser;

use App\Enums\Role;
use App\Events\PendingUserRemoved;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RemovePendingMemberTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->withRole(Role::Administrator)->create();
    }

    public function test_remove_pending_member()
    {
        $user = User::factory()->pending()->create();
        $userId = $user->id;

        Event::fake();

        $response = $this->actingAs($this->admin)->delete(route('beheer.pending-users.destroy', $user));

        $response->assertRedirect(route('beheer.pending-users.index'));
        Event::assertDispatched(PendingUserRemoved::class);

        $this->assertNull(User::find($userId));
    }
}
