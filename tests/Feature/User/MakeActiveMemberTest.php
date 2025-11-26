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
    
    public function test_make_old_member_active_member()
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Config::get('constants.Administrator'));
        
        $user = User::factory()->create();
        $user->lid_af = Carbon::now();
        $userId = $user->id;
        
        Event::fake();
        
        $response = $this->actingAs($admin)->patch(route('users.makeActiveMember', $user));
        
        $response->assertRedirect(route('users.show', $user));
        Event::assertDispatched(OldMemberBecameMember::class);
        
        $this->assertNull(User::find($userId)->lid_af);
    }
}
