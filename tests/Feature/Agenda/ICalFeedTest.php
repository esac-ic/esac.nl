<?php

namespace Tests\Feature\Agenda;

use App\Models\AgendaItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ICalFeedTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_feed_lists_every_agenda_item_in_local_time()
    {
        $item = AgendaItem::factory()->create([
            'title' => 'Climbing weekend',
            'text' => '<p>Bring a rope &amp; harness</p>',
            'start_date' => '2026-05-01 10:00:00',
            'end_date' => '2026-05-03 18:30:00',
        ]);
        AgendaItem::factory()->create();

        $response = $this->get('/ical');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/calendar; charset=utf-8');
        $response->assertDownload('cal.ics');

        $ics = str_replace("\r\n ", '', $response->getContent());
        $this->assertSame(2, substr_count($ics, 'BEGIN:VEVENT'));
        $this->assertStringContainsString('BEGIN:VTIMEZONE', $ics);
        $this->assertStringContainsString('SUMMARY:Climbing weekend', $ics);
        $this->assertStringContainsString("DTSTART:20260501T100000\r\n", $ics);
        $this->assertStringContainsString("DTEND:20260503T183000\r\n", $ics);
        $this->assertStringContainsString('UID:agenda-item-'.$item->id.'@localhost', $ics);
        $this->assertStringContainsString('Bring a rope & harness', $ics);
        $this->assertStringContainsString('Meer informatie: '.url('/agenda/'.$item->id), $ics);
    }
}
