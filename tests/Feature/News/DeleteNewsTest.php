<?php

namespace Tests\Feature;

use App\ApplicationResponse;
use App\ApplicationResponseRow;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\NewsItem;
use App\Rol;
use App\User;
use Artisan;
use Carbon\Carbon;
use TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RemoveNewsItemTest extends TestCase
{
    use DatabaseMigrations;

    private $url = 'newsItems';
    private $newsItem;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = factory(User::class)->create();
        $role = factory(Rol::class)->create([
            'id' => 3
        ]);

        $user->roles()->attach($role->id);
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
