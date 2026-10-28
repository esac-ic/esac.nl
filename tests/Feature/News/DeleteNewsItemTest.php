<?php

namespace Tests\Feature\News;
use App\Models\NewsItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteNewsItemTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'newsItems';
    private $newsItem;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = User::factory()->create();

        $user->roles()->attach(Config::get('constants.Activity_administrator'));
        $this->be($user);

        session()->start();
    }

    #[Test]
    public function DeleteNewsItem(){
        $newsItem = NewsItem::factory()->create();

        $response = $this->delete($this->url . '/' . $newsItem->id);

        $response->assertStatus(302);

        $this->assertNull(NewsItem::find($newsItem->id));
    }
}
