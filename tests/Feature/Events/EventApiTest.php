<?php

namespace Tests\Feature\Events;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_events_api_filters_by_category()
    {
        $category = EventCategory::factory()->create();
        $event = Event::factory()->for($category, 'category')->create();
        Event::factory()->count(2)->create();

        $this->getJson(route('api.events.index', ['category' => $category->id]))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', $event->title);
        $this->getJson(route('api.events.index'))->assertJsonCount(3, 'data');
    }

    #[Test]
    public function the_events_api_lists_the_events_ending_on_or_after_the_date()
    {
        Event::factory()->create(['start_date' => '2026-05-01 10:00:00', 'end_date' => '2026-05-01 12:00:00']);
        $later = Event::factory()->create(['start_date' => '2026-07-01 10:00:00', 'end_date' => '2026-07-01 12:00:00']);

        $this->getJson(route('api.events.index', ['from' => '2026-06-01']))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $later->id);
    }

    #[Test]
    public function a_date_that_isnt_year_month_day_is_rejected()
    {
        $this->getJson(route('api.events.index', ['from' => '01-06-2026']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('from');
    }

    #[Test]
    public function the_events_api_shows_nine_events_a_page()
    {
        Event::factory()->count(10)->create();

        $this->getJson(route('api.events.index', ['page' => 2]))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.last_page', 2);
    }
}
