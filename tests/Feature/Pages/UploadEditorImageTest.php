<?php

namespace Tests\Feature\Pages;

use App\Enums\AdminRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UploadEditorImageTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function only_images_can_be_uploaded()
    {
        Storage::fake('public');
        $admin = User::factory()->create();
        $admin->update(['roles' => [AdminRole::ContentAdministrator]]);
        $this->actingAs($admin);

        $this->post('admin/editor-images', ['image' => UploadedFile::fake()->createWithContent('shell.php', '<?php echo 1;')])
            ->assertInvalid('image');
        $this->post('admin/editor-images', ['image' => UploadedFile::fake()->image('photo.jpg')])->assertOk();

        $this->assertCount(1, Storage::disk('public')->files('image_uploads'));
    }
}
