<?php

namespace Tests\Feature\User;

use App\Events\OldMemberBecameMember;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

class MakeActiveMemberTest extends \TestCase
{
    use RefreshDatabase;
    
    private User $admin;
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach(Config::get('constants.Administrator'));
    }
    
    public function test_make_old_member_active_member()
    {
        $user = User::factory()->create();
        $user->lid_af = Carbon::now();
        $userId = $user->id;
        
        Event::fake();
        
        $response = $this->actingAs($this->admin)->patch(route('users.makeActiveMember', $user));
        
        $response->assertRedirect(route('users.show', $user));
        Event::assertDispatched(OldMemberBecameMember::class);
        
        $this->assertNull(User::find($userId)->lid_af);
    }
}
