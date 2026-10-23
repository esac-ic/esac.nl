<?php

namespace Tests\Feature\Members;

use App\Enums\AdminRole;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MemberRegistrationsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_administrator_sees_a_members_registrations()
    {
        $member = User::factory()->create();
        Registration::factory()->create([
            'user_id' => $member->id,
            'event_id' => Event::factory()->create(['title' => 'Ice climbing weekend']),
        ]);
        $admin = User::factory()->withRole(AdminRole::Administrator)->create();

        $this->actingAs($admin)
            ->get(route('admin.members.show', $member))
            ->assertOk()
            ->assertSee('Ice climbing weekend');
    }
}
