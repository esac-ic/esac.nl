<?php

namespace Tests\Feature;

use App\ApplicationResponse;
use App\ApplicationResponseRow;
use Config;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\NewsItem;
use App\Rol;
use App\User;
use Artisan;
use Carbon\Carbon;
use TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateNewsItemTest extends TestCase
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
    public function CreateNewsItem(){
        $body = [
            '_token' => csrf_token(),
            'NL_title' => 'test nieuws',
            'EN_title' => 'test news',
            'NL_text' => 'test nieuws',
            'EN_text' => 'test news',
            'author' => 'Gebruiker1'
        ];
        $response = $this->post($this->url, $body);
        $response->assertStatus(302);
        $newsItem = NewsItem::all()->last();

        $this->assertEquals($body['NL_text'],$newsItem->newsItemText->text());
        $this->assertEquals($body['NL_title'],$newsItem->newsItemTitle->text());
        $this->assertEquals($body['author'],$newsItem->author);
    }
}
