<?php

namespace Tests\Feature;

use App\Models\HeaderSlide;
use App\Models\Sponsor;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\WebsiteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_development_data_seeds_without_mailman()
    {
        Storage::fake('public');
        Queue::fake();

        $this->seed(DatabaseSeeder::class);
        $this->seed(WebsiteSeeder::class);

        $this->assertTrue(User::query()->where('email', 'test@esac.nl')->sole()->can('access-admin'));
        $this->assertSame(9, HeaderSlide::query()->count());
        $this->assertSame(4, Sponsor::query()->count());
        $this->assertTrue(Sponsor::query()->first()->hasMedia('logo'));
    }
}
