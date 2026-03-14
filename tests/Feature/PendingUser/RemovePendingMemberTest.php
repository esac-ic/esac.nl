<?php

namespace Tests\Feature\PendingUser;

use App\Events\PendingUserRemoved;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

class RemovePendingMemberTest extends \TestCase
{
    use RefreshDatabase;
    
    private User $admin;
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach(Config::get('constants.Administrator'));
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
