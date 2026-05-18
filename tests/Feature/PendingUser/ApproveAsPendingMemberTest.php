<?php

namespace Tests\Feature\PendingUser;

use App\Events\PendingUserApproved;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

class ApproveAsPendingMemberTest extends \TestCase
{
    use RefreshDatabase;
    
    private User $admin;
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach(Config::get('constants.Administrator'));
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
