<?php

namespace Tests\Feature\News;

use App\Enums\AdminRole;
use App\Models\NewsItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateNewsItemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->withRole(AdminRole::EventAdministrator)->create());
    }

    public static function newsItems(): array
    {
        return [
            'plain' => [['title' => 'test nieuws', 'body' => 'test nieuws', 'author' => 'Gebruiker1']],
            'emoji' => [['title' => 'test news 😀', 'body' => 'test nieuws 😀', 'author' => 'Gebruiker1']],
        ];
    }

    #[Test]
    #[DataProvider('newsItems')]
    public function a_content_administrator_can_create_a_news_item(array $body)
    {
        $this->post('admin/news-items', $body)->assertRedirect();

        $this->assertDatabaseHas(NewsItem::class, $body);
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
