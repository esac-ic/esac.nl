<?php

namespace Tests\Feature\User;

use App\CustomClasses\MailList\MailListFacade;
use App\Events\MemberBecameOldMember;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;

class RemoveAsActiveMemberTest extends \TestCase
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
        
        Event::fake();
        
        $this->mock(MailListFacade::class, function (MockInterface $mock) use ($userId) {
            //TODO possibly figure out how to check if the function is called with the same user as the request
            //as of writing the test this gives errors :/
            $mock->shouldReceive('deleteUserFormAllMailList')->once(); //the method is a typo :/
        });
        
        $response = $this->actingAs($this->admin)->patch(route('users.removeAsActiveMember', $user));
        
        $response->assertRedirect(route('users.show', $user));
        Event::assertDispatched(MemberBecameOldMember::class);
        
        $this->assertNotNull(User::find($userId)->lid_af);
    }
}
