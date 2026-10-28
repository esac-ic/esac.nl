<?php

namespace Tests\Feature\Management;

use App\Enums\Role;
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

        $this->admin = User::factory()->create();
        $this->admin->roles()->attach(Role::Administrator->value);
    }

    #[Test]
    public function an_administrator_can_view_and_update_settings()
    {
        $setting = Setting::where('name', Setting::SETTING_NORMAL_MEMBER_MAIL_LISTS)->first();

        $this->actingAs($this->admin)->get('beheer/settings')
            ->assertOk()
            ->assertSee($setting->value);

        $this->actingAs($this->admin)->get('beheer/settings/edit')->assertOk();

        $this->actingAs($this->admin)
            ->put('beheer/settings', ['setting' => [$setting->name => 'updated-value']])
            ->assertRedirect('beheer/settings');

        $this->assertSame('updated-value', $setting->fresh()->value);
    }

    #[Test]
    public function a_blank_setting_is_saved_as_empty()
    {
        $setting = Setting::where('name', Setting::SETTING_NORMAL_MEMBER_MAIL_LISTS)->first();

        $this->actingAs($this->admin)
            ->put('beheer/settings', ['setting' => [$setting->name => '']])
            ->assertRedirect('beheer/settings');

        $this->assertSame('', $setting->fresh()->value);
    }

    #[Test]
    public function a_member_without_the_ability_can_not_view_settings()
    {
        $member = User::factory()->create();

        $this->actingAs($member)->get('beheer/settings')->assertStatus(403);
    }
}
