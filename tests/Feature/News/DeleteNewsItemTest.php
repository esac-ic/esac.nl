<?php

namespace Tests\Feature\News;
use App\Enums\Role;
use App\Models\NewsItem;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteNewsItemTest extends TestCase
{
    use DatabaseMigrations;

    private $url = 'newsItems';
    private $newsItem;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $user = User::factory()->create();

        $user->roles()->attach(Role::ActivityAdministrator->value);
        $this->be($user);

        session()->start();
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
    #[Test]
    public function DeleteNewsItem(){
        $newsItem = NewsItem::factory()->create();

        $response = $this->delete($this->url . '/' . $newsItem->id);

        $response->assertStatus(302);

        $this->assertNull(NewsItem::find($newsItem->id));
    }
}
