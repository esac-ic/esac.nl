<?php

namespace Tests\Feature\News;

use App\Enums\AdminRole;
use App\Models\NewsItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ManageNewsItemsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->withRole(AdminRole::EventAdministrator)->create());
    }

    #[Test]
    public function an_event_administrator_can_manage_news_items()
    {
        $this->post('admin/news-items', ['title' => 'Test news 😀', 'body' => 'Test news', 'author' => 'Author'])->assertRedirect();
        $newsItem = NewsItem::sole();
        $this->assertSame('Test news 😀', $newsItem->title);

        $this->get('admin/news-items')->assertOk()->assertSee('Test news 😀');
        $this->patch('admin/news-items/'.$newsItem->id, ['title' => 'Updated news', 'body' => 'Updated', 'author' => 'Author'])->assertRedirect();
        $this->assertSame('Updated news', $newsItem->fresh()->title);

        $this->delete('admin/news-items/'.$newsItem->id)->assertRedirect();
        $this->assertSoftDeleted($newsItem);
    }

    #[Test]
    public function creating_a_news_item_stores_a_resized_header_and_thumbnail()
    {
        Storage::fake('public');

        $this->post('admin/news-items', [
            'title' => 'test nieuws',
            'body' => 'test nieuws',
            'author' => 'Gebruiker1',
            'image' => UploadedFile::fake()->image('photo.jpg', 1600, 1200),
        ])->assertRedirect();
        $newsItem = NewsItem::sole();

        $this->assertSame([1200, 500], array_slice(getimagesize(Storage::disk('public')->path($newsItem->image_path)), 0, 2));
        $this->assertSame([400, 300], array_slice(getimagesize(Storage::disk('public')->path($newsItem->thumbnail_path)), 0, 2));
    }

    #[Test]
    public function a_news_item_needs_an_author()
    {
        $this->post('admin/news-items', ['title' => 'test nieuws', 'body' => 'test nieuws'])->assertInvalid('author');

        $this->assertDatabaseEmpty(NewsItem::class);
    }
}
