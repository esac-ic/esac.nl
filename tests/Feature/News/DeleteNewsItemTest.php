<?php

namespace Tests\Feature\News;
use App\NewsItem;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use TestCase;

class DeleteNewsItemTest extends TestCase
{
    use DatabaseMigrations;

    private $url = 'newsItems';
    private $newsItem;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = factory(User::class)->create();

        $user->roles()->attach(Config::get('constants.Activity_administrator'));
        $this->be($user);

        session()->start();
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
    /** @test */
    public function DeleteNewsItem(){
        $newsItem = factory(NewsItem::class)->create();

        $response = $this->delete($this->url . '/' . $newsItem->id);

        $response->assertStatus(302);

        $this->assertNull(NewsItem::find($newsItem->id));
    }
}
