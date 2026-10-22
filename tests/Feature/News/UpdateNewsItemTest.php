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

    #[Test]
    public function update_news_item()
    {
        $newsItem = NewsItem::factory()->create();
        $body = ['title' => 'test news', 'text' => 'test nieuws', 'author' => 'Gebruiker1'];
        $this->actingAs(User::factory()->withRole(Role::ActivityAdministrator)->create());

        $this->patch('beheer/news-items/'.$newsItem->id, $body)->assertRedirect();

        $this->assertDatabaseHas(NewsItem::class, ['id' => $newsItem->id, ...$body]);
    }
}
