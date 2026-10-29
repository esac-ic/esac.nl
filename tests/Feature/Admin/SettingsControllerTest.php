<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_member_without_the_ability_can_not_view_settings()
    {
        $member = User::factory()->create();

        $this->actingAs($member)->get('admin/settings/edit')->assertStatus(403);
    }
}
