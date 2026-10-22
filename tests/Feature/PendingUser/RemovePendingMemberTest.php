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
        
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach(Role::Administrator->value);
    }
    
    public function test_remove_pending_member()
    {
        $user = User::factory()->pending()->create();
        $userId = $user->id;
        
        Event::fake();
        
        $response = $this->actingAs($this->admin)->patch(route('users.removeAsPendingMember', $user));
        
        $response->assertRedirect(route('users.indexPendingMembers'));
        Event::assertDispatched(PendingUserRemoved::class);
        
        $this->assertNull(User::find($userId));
    }
}
