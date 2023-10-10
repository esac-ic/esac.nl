<?php

namespace Tests\Feature\News;

use App\NewsItem;
use App\User;
use Artisan;
use Config;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use TestCase;

class UpdateNewsItemTest extends TestCase
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
    public function UpdateNewsItem(){
        $newsItem = factory(NewsItem::class)->create();
        $body = [
            '_token' => csrf_token(),
            'title' => 'test news',
            'text' => 'test nieuws',
            'author' => 'Gebruiker1'
        ];

        $response = $this->patch($this->url . "/" . $newsItem->id, $body);

        $response->assertStatus(302);

        $newsItem = $newsItem->refresh();

        $this->assertEquals($body['text'],$newsItem->text);
        $this->assertEquals($body['title'],$newsItem->title);
        $this->assertEquals($body['author'],$newsItem->author);
    }
}
