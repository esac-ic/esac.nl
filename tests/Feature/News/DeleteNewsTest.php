<?php

namespace Tests\Feature;
use Config;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\NewsItem;
use App\User;
use Artisan;
use TestCase;

class RemoveNewsItemTest extends TestCase
{
    use DatabaseMigrations;

    private $url = 'newsItems';
    private $newsItem;

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
