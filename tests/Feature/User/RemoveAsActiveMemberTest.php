<?php

namespace Tests\Feature\User;

use App\Enums\AdminRole;
use App\Events\MemberBecameOldMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RemoveAsActiveMemberTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->withRole(AdminRole::Administrator)->create();
    }

    public function test_remove_as_active_member()
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $this->assertNull($user->membership_ended_on); // sanity check in case the factory breaks

        // fake events, but make sure the model events are handled normally
        $initialDispenser = Event::getFacadeRoot();
        Event::fake();
        Model::setEventDispatcher($initialDispenser);

        $response = $this->actingAs($this->admin)->delete(route('admin.users.membership.destroy', $user));
        $response->assertRedirect(route('admin.users.show', $user));
        Event::assertDispatched(MemberBecameOldMember::class);

        $this->assertNotNull(User::find($userId)->membership_ended_on);
    }
}
