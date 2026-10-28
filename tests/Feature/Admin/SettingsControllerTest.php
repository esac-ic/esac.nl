<?php

namespace Tests\Feature\Admin;

use App\Enums\AdminRole;
use App\Enums\SettingKey;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->withRole(AdminRole::Administrator)->create();
    }

    #[Test]
    public function an_administrator_can_view_and_update_settings()
    {
        $setting = Setting::where('name', SettingKey::MemberMailLists)->first();

        $this->actingAs($this->admin)->get('admin/settings/edit')
            ->assertOk()
            ->assertSee($setting->value);

        $this->actingAs($this->admin)
            ->put('admin/settings', ['settings' => [$setting->name->value => 'updated-value']])
            ->assertRedirect('admin/settings/edit');

        $this->assertSame('updated-value', $setting->fresh()->value);
    }

    #[Test]
    public function a_blank_setting_is_saved_as_empty()
    {
        $setting = Setting::where('name', SettingKey::MemberMailLists)->first();

        $this->actingAs($this->admin)
            ->put('admin/settings', ['settings' => [$setting->name->value => '']])
            ->assertRedirect('admin/settings/edit');

        $this->assertSame('', $setting->fresh()->value);
    }

    #[Test]
    public function a_member_without_the_ability_can_not_view_settings()
    {
        $member = User::factory()->create();

        $this->actingAs($member)->get('admin/settings/edit')->assertStatus(403);
    }
}
