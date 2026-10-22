<?php

namespace Tests\Feature\News;

use App\Enums\Role;
use App\Models\NewsItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateNewsItemTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'newsItems';

    private $newsItem;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = User::factory()->create();

        $user->roles()->attach(Role::ActivityAdministrator->value);
        $this->be($user);
    }

    #[Test]
    public function update_news_item()
    {
        $newsItem = NewsItem::factory()->create();
        $body = [
            'title' => 'test news',
            'text' => 'test nieuws',
            'author' => 'Gebruiker1',
        ];

        $response = $this->patch($this->url.'/'.$newsItem->id, $body);

        $response->assertStatus(302);

        $newsItem = $newsItem->refresh();

        $this->assertEquals($body['text'], $newsItem->text);
        $this->assertEquals($body['title'], $newsItem->title);
        $this->assertEquals($body['author'], $newsItem->author);
    }
}
