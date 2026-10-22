<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event as CalendarEvent;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Illuminate\Http\Response;

/**
 * The events as an iCal feed that calendar apps subscribe to.
 */
class EventFeedController extends Controller
{
    public function __invoke(): Response
    {
        $calendar = new Calendar([]);
        $host = parse_url(config('app.url'), PHP_URL_HOST);

        // Old events only bloat the feed, so skip those that ended over a year ago.
        $events = Event::query()->where('end_date', '>=', now()->subYear()->startOfDay())->get();

        foreach ($events as $event) {
            // The UID keeps its old prefix, so subscribed calendars don't duplicate the events.
            $calendarEvent = new CalendarEvent(new UniqueIdentifier('agenda-item-'.$event->id.'@'.$host));
            $calendarEvent
                ->setOccurrence(new TimeSpan(
                    // In UTC, so the times are absolute.
                    new DateTime($event->start_date->toImmutable()->utc(), true),
                    new DateTime($event->end_date->toImmutable()->utc(), true),
                ))
                ->setSummary($event->title)
                ->setDescription(
                    html_entity_decode(strip_tags($event->text)).
                    "\r\n\r\n----------\r\n".
                    'More information: '.route('events.show', $event)
                );
            $calendar->addEvent($calendarEvent);
        }

        return response((string) new CalendarFactory()->createCalendar($calendar), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="cal.ics"',
        ]);
    }
}
