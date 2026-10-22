<?php

namespace Tests\Feature\Pages;

use App\Enums\AdminRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteEditorImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_editor_image_is_deleted_by_its_src()
    {
        Storage::fake('public')->put('image_uploads/a.jpg', 'a');
        $admin = User::factory()->withRole(AdminRole::ContentAdministrator)->create();

        $this->actingAs($admin)->delete('admin/images?path=/storage/image_uploads/a.jpg')->assertOk();

        Storage::disk('public')->assertMissing('image_uploads/a.jpg');
    }
}
