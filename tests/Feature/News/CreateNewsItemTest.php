<?php

namespace Tests\Feature\News;

use App\Enums\Role;
use App\Models\NewsItem;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateNewsItemTest extends TestCase
{
    use DatabaseMigrations;

    private $url = 'newsItems';
    private $newsItem;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = User::factory()->create();

        $user->roles()->attach(Role::ActivityAdministrator->value);
        $this->be($user);

        session()->start();
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
    #[Test]
    public function CreateNewsItem(){
        $body = [
            '_token' => csrf_token(),
            'title' => 'test nieuws',
            'text' => 'test nieuws',
            'author' => 'Gebruiker1'
        ];
        $response = $this->post($this->url, $body);
        $response->assertStatus(302);
        $newsItem = NewsItem::all()->last();

        $this->assertEquals($body['text'],$newsItem->text);
        $this->assertEquals($body['title'],$newsItem->title);
        $this->assertEquals($body['author'],$newsItem->author);
    }

    #[Test]
    public function creating_a_news_item_stores_a_resized_header_and_thumbnail()
    {
        Storage::fake('public');

        $response = $this->post($this->url, [
            'title' => 'test nieuws',
            'text' => 'test nieuws',
            'author' => 'Gebruiker1',
            'thumbnail' => UploadedFile::fake()->image('photo.jpg', 1600, 1200),
        ]);
        $response->assertStatus(302);
        $newsItem = NewsItem::all()->last();

        $this->assertSame([1200, 500], array_slice(getimagesize(Storage::disk('public')->path($newsItem->image_url)), 0, 2));
        $this->assertSame([400, 300], array_slice(getimagesize(Storage::disk('public')->path($newsItem->thumbnail_url)), 0, 2));
    }

    #[Test]
    public function a_news_item_needs_an_author()
    {
        $response = $this->post($this->url, ['title' => 'test nieuws', 'text' => 'test nieuws']);

        $response->assertSessionHasErrors('author');
        $this->assertSame(0, NewsItem::count());
    }

    public function CreateNewsItemWithEmoji(){
        $body = [
            '_token' => csrf_token(),
            'title' => 'test news 😀',
            'text' => 'test nieuws 😀',
            'author' => 'Gebruiker1'
        ];
        $response = $this->post($this->url, $body);
        $response->assertStatus(302);
        $newsItem = NewsItem::all()->last();

        $this->assertEquals($body['text'],$newsItem->text);
        $this->assertEquals($body['title'],$newsItem->title);
        $this->assertEquals($body['author'],$newsItem->author);
    }
}
