<?php

namespace Tests\Feature\User;

use App\CustomClasses\MailList\MailListFacade;
use App\Events\MemberBecameOldMember;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
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
        
        //fake events, but make sure the model events are handled normally
        $initialDispenser = Event::getFacadeRoot();
        Event::fake();
        Model::setEventDispatcher($initialDispenser);
        
        $this->mock(MailListFacade::class, function (MockInterface $mock) use ($user) {
            $mock->shouldReceive('deleteUserForAllMailList')
                ->with(\Mockery::type(User::class))
                ->with(\Mockery::on(function ($arg) use ($user) { //custom validator to check if the correct argument is passed
                    return $user->is($arg);
                }))
                ->once();
        });
        
        $response = $this->actingAs($this->admin)->patch(route('users.removeAsActiveMember', $user));
        $response->assertRedirect(route('users.show', $user));
        Event::assertDispatched(MemberBecameOldMember::class);
        
        $this->assertNotNull(User::find($userId)->lid_af);
    }
}
