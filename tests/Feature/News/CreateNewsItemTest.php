<?php

namespace Tests\Feature\News;

use App\NewsItem;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use TestCase;

class CreateNewsItemTest extends TestCase
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
