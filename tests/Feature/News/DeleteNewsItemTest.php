<?php

namespace Tests\Feature\News;

use App\Enums\AdminRole;
use App\Models\NewsItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteNewsItemTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_content_administrator_can_delete_a_news_item()
    {
        $newsItem = NewsItem::factory()->create();
        $this->actingAs(User::factory()->withRole(AdminRole::EventAdministrator)->create());

        $this->delete('admin/news-items/'.$newsItem->id)->assertRedirect();

        $this->assertSoftDeleted($newsItem);
    }
}
