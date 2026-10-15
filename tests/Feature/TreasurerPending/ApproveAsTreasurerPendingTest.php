<?php

namespace Tests\Feature\PendingUser;

use App\Events\TreasurerPendingApproved;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

class ApproveAsTreasurerPendingTest extends \TestCase
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
        $user = User::factory()->treasurer_pending()->create();
        $userId = $user->id;
        
        Event::fake();
        
        $response = $this->actingAs($this->admin)->patch(route('users.approveAsTreasurerPending', $user));
        
        $response->assertRedirect(route('users.indexTreasurerPending'));
        Event::assertDispatched(TreasurerPendingApproved::class);
        
        $this->assertNull(User::find($userId)->treasurer_pending);
    }
}
