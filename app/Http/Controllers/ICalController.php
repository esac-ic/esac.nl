<?php

namespace App\Http\Controllers;

use App\Models\AgendaItem;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Illuminate\Http\Response;

class ICalController extends Controller
{
    public function getAgendaItemsICalObject(): Response
    {
        $calendar = new Calendar([]);
        $host = parse_url(config('app.url'), PHP_URL_HOST);

        // Old events only bloat the feed, so skip those that ended over a year ago.
        $agendaItems = AgendaItem::where('end_date', '>=', now()->subYear()->startOfDay())->get();

        foreach ($agendaItems as $agendaItem) {
            $event = new Event(new UniqueIdentifier('agenda-item-'.$agendaItem->id.'@'.$host));
            $event
                ->setOccurrence(new TimeSpan(
                    // In UTC, so the times are absolute.
                    new DateTime($agendaItem->start_date->toImmutable()->utc(), true),
                    new DateTime($agendaItem->end_date->toImmutable()->utc(), true),
                ))
                ->setSummary($agendaItem->title)
                ->setDescription(
                    html_entity_decode(strip_tags($agendaItem->text)).
                    "\r\n\r\n----------\r\n".
                    'Meer informatie: '.route('agenda.detail', $agendaItem)
                );
            $calendar->addEvent($event);
        }

        return response((string) new CalendarFactory()->createCalendar($calendar), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="cal.ics"',
        ]);
    }
}
