<?php

namespace Tests\Feature\Members;

use App\Enums\AdminRole;
use App\Events\MembershipEnded;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EndMembershipTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->withRole(AdminRole::Administrator)->create();
    }

    #[Test]
    public function ending_a_membership_makes_the_member_a_former_member()
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $this->assertNull($user->membership_ended_on); // sanity check in case the factory breaks

        // fake events, but make sure the model events are handled normally
        $initialDispenser = Event::getFacadeRoot();
        Event::fake();
        Model::setEventDispatcher($initialDispenser);

        $response = $this->actingAs($this->admin)->delete(route('admin.members.membership.destroy', $user));
        $response->assertRedirect(route('admin.members.show', $user));
        Event::assertDispatched(MembershipEnded::class);

        $this->assertNotNull(User::find($userId)->membership_ended_on);
    }

    #[Test]
    public function ending_a_membership_logs_the_member_out_everywhere()
    {
        $user = User::factory()->create(['remember_token' => 'remembered']);
        DB::table('sessions')->insert(['id' => 'session', 'user_id' => $user->id, 'payload' => '', 'last_activity' => time()]);

        $this->actingAs($this->admin)->delete(route('admin.members.membership.destroy', $user));

        $this->assertNotSame('remembered', $user->fresh()->getRememberToken());
        $this->assertDatabaseMissing('sessions', ['user_id' => $user->id]);
    }
}
