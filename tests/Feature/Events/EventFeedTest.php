<?php

namespace Tests\Feature\Events;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventFeedTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_feed_lists_the_events_from_a_year_ago_onward_in_utc()
    {
        $this->travelTo('2026-06-01');
        $item = Event::factory()->create([
            'title' => 'Climbing weekend',
            'text' => '<p>Bring a rope &amp; harness</p>',
            'start_date' => '2026-05-01 10:00:00',
            'end_date' => '2026-05-03 18:30:00',
        ]);
        Event::factory()->create(['start_date' => '2027-01-01', 'end_date' => '2027-01-01']);
        Event::factory()->create(['start_date' => '2025-05-01', 'end_date' => '2025-05-02']);

        $response = $this->get('/events.ics');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/calendar; charset=utf-8');
        $response->assertDownload('cal.ics');

        $ics = str_replace("\r\n ", '', $response->getContent());
        $this->assertSame(2, substr_count($ics, 'BEGIN:VEVENT'));
        $this->assertStringContainsString('SUMMARY:Climbing weekend', $ics);
        // Amsterdam summer and winter time.
        $this->assertStringContainsString("DTSTART:20260501T080000Z\r\n", $ics);
        $this->assertStringContainsString("DTEND:20260503T163000Z\r\n", $ics);
        $this->assertStringContainsString("DTSTART:20261231T230000Z\r\n", $ics);
        $this->assertStringContainsString('UID:agenda-item-'.$item->id.'@localhost', $ics);
        $this->assertStringContainsString('Bring a rope & harness', $ics);
        $this->assertStringContainsString('More information: '.url('/events/'.$item->id), $ics);
    }
}
