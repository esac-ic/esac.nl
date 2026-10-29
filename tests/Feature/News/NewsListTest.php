<?php

namespace Tests\Feature\News;

use App\Models\NewsItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NewsListTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_excerpt_shows_the_text_without_its_markup()
    {
        NewsItem::factory()->create(['body' => "<p>ESAC's <b>climbing</b> weekend</p>"]);

        $this->get('news')->assertOk()->assertSee("ESAC's climbing weekend");
    }
}
