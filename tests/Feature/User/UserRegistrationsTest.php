<?php

namespace Tests\Feature\User;

use App\Enums\Role;
use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRegistrationsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_certificate_administrator_sees_a_members_registrations()
    {
        $member = User::factory()->create();
        ApplicationResponse::factory()->create([
            'user_id' => $member->id,
            'agenda_id' => AgendaItem::factory()->create(['title' => 'Ice climbing weekend']),
        ]);
        $admin = User::factory()->withRole(Role::CertificateAdministrator)->create();

        $this->actingAs($admin)
            ->getJson(route('api.registrations.index', ['user_id' => $member->id]))
            ->assertOk()
            ->assertJsonPath('0.name', 'Ice climbing weekend');
    }
}
