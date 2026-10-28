<?php

namespace Tests\Feature\User;

use App\Events\MemberBecameOldMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RemoveAsActiveMemberTest extends TestCase
{
    use RefreshDatabase;
    
    private User $admin;
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach(Config::get('constants.Administrator'));
    }
    
    public function test_remove_as_active_member()
    {
        $user = User::factory()->create();
        $userId = $user->id;
        
        $this->assertNull($user->lid_af); //sanity check in case the factory breaks
        
        //fake events, but make sure the model events are handled normally
        $initialDispenser = Event::getFacadeRoot();
        Event::fake();
        Model::setEventDispatcher($initialDispenser);
        
        $response = $this->actingAs($this->admin)->patch(route('users.removeAsActiveMember', $user));
        $response->assertRedirect(route('users.show', $user));
        Event::assertDispatched(MemberBecameOldMember::class);
        
        $this->assertNotNull(User::find($userId)->lid_af);
    }

    public function test_removing_an_active_member_logs_them_out_everywhere()
    {
        $user = User::factory()->create(['remember_token' => 'remembered']);
        DB::table('sessions')->insert(['id' => 'session', 'user_id' => $user->id, 'payload' => '', 'last_activity' => time()]);

        $this->actingAs($this->admin)->patch(route('users.removeAsActiveMember', $user));

        $this->assertNotSame('remembered', $user->fresh()->getRememberToken());
        $this->assertDatabaseMissing('sessions', ['user_id' => $user->id]);
    }
}
