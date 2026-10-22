<?php

namespace Tests\Feature\User;

use App\Enums\AdminRole;
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
            'agenda_item_id' => AgendaItem::factory()->create(['title' => 'Ice climbing weekend']),
        ]);
        $admin = User::factory()->withRole(AdminRole::CertificateAdministrator)->create();

        $this->actingAs($admin)
            ->getJson(route('api.registrations.index', ['user_id' => $member->id]))
            ->assertOk()
            ->assertJsonPath('0.name', 'Ice climbing weekend');
    }
}
