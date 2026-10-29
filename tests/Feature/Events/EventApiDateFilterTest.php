<?php

namespace Tests\Feature\Events;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventApiDateFilterTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_events_api_filters_by_date()
    {
        Event::factory()->create(['start_date' => '2026-05-01 10:00:00', 'end_date' => '2026-05-01 12:00:00']);
        Event::factory()->create(['start_date' => '2026-07-01 10:00:00', 'end_date' => '2026-07-01 12:00:00']);

        $this->getJson('api/agenda?startDate=01-06-2026')
            ->assertOk()
            ->assertJsonPath('agendaItemCount', 1);
    }

    #[Test]
    public function a_malformed_date_is_rejected()
    {
        $this->getJson('api/agenda?startDate=2026-06-01')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('startDate');
    }
}
