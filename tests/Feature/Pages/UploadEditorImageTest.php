<?php

namespace Tests\Feature\Pages;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadEditorImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_images_can_be_uploaded()
    {
        Storage::fake('public');
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::ContentAdministrator->value);
        $this->actingAs($admin);

        $this->post('images/upload', ['image' => UploadedFile::fake()->createWithContent('shell.php', '<?php echo 1;')])
            ->assertInvalid('image');
        $url = $this->post('images/upload', ['image' => UploadedFile::fake()->image('photo.jpg')])->assertOk()->getContent();

        $this->assertStringStartsWith('/storage/image_uploads/', $url);

        $this->assertCount(1, Storage::disk('public')->files('image_uploads'));
    }
}
