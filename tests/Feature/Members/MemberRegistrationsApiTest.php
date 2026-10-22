<?php

namespace Tests\Feature\Members;

use App\Enums\AdminRole;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MemberRegistrationsApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_certificate_administrator_sees_a_members_registrations()
    {
        $member = User::factory()->create();
        Registration::factory()->create([
            'user_id' => $member->id,
            'event_id' => Event::factory()->create(['title' => 'Ice climbing weekend']),
        ]);
        $admin = User::factory()->withRole(AdminRole::CertificateAdministrator)->create();

        $this->actingAs($admin)
            ->getJson(route('api.registrations.index', ['user_id' => $member->id]))
            ->assertOk()
            ->assertJsonPath('0.name', 'Ice climbing weekend');
    }
}
