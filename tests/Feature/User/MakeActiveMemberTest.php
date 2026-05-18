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
        $user->save();
        
        $userId = $user->id;
        
        Event::fake();
        
        $response = $this->actingAs($this->admin)->patch(route('users.makeActiveMember', $user));
        
        $response->assertRedirect(route('users.show', $user));
        Event::assertDispatched(OldMemberBecameMember::class);
        
        $this->assertNull(User::find($userId)->lid_af); //use User::find instead of $user to check if the user is properly updated in the db
    }
    
    public function test_make_active_member_active()
    {
        $user = User::factory()->create();
        $user->lid_af = null;
        $user->save();
        
        Event::fake();
        
        $response = $this->actingAs($this->admin)->patch(route('users.makeActiveMember', $user));
        $response->assertRedirect(route('users.index-old-members'));
        Event::assertNotDispatched(OldMemberBecameMember::class);
    }
    
    public function test_make_member_without_email_active()
    {
        $user = User::factory()->create();
        $user->lid_af = Carbon::now();
        $user->email = null;
        $user->save();
        
        $userId = $user->id;
        
        Event::fake();
        
        $response = $this->actingAs($this->admin)->patch(route('users.makeActiveMember', $user));
        $response->assertRedirect(route('users.index-old-members'));
        Event::assertNotDispatched(OldMemberBecameMember::class);
        
        $this->assertNotNull(User::find($userId)->lid_af);
    }
    
    public function test_make_active_member_without_email_active()
    {
        $user = User::factory()->create();
        $user->lid_af = null;
        $user->email = null;
        $user->save();
        
        Event::fake();
        
        $response = $this->actingAs($this->admin)->patch(route('users.makeActiveMember', $user));
        $response->assertRedirect(route('users.index-old-members'));
        Event::assertNotDispatched(OldMemberBecameMember::class);
    }
}
