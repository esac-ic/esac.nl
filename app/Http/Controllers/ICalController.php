<?php

namespace App\Http\Controllers;

use App\AgendaItem;
use DateTimeImmutable;
use DateTimeZone as PhpDateTimeZone;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\Entity\TimeZone;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;

class ICalController extends Controller
{
    public function getAgendaItemsICalObject()
    {
        $phpDateTimeZone = new PhpDateTimeZone('Europe/Amsterdam');
        $timeZone = TimeZone::createFromPhpDateTimeZone(
            $phpDateTimeZone,
            new DateTimeImmutable(date("Y", strtotime("-1 year")) . '-' . date("m") . '-' . date("d") . ' 00:00:00', $phpDateTimeZone),
            new DateTimeImmutable(date("Y", strtotime("+1 year")) . '-' . date("m") . '-' . date("d") . ' 00:00:00', $phpDateTimeZone),
        );

        $agenda_items = AgendaItem::all();
        $calendar = new Calendar([]);
        $calendar->addTimeZone($timeZone);

        // create Ical events from agenda items
        foreach ($agenda_items as $agenda_item) {
            $event = new Event();
            $event
                ->setOccurrence(new TimeSpan(new DateTime(new \DateTime($agenda_item->startDate), false), new DateTime(new \DateTime($agenda_item->endDate), false)))
                ->setSummary($agenda_item->title)
                ->setDescription(
                    html_entity_decode(strip_tags($agenda_item->text)) .
                    "\r\n\r\n----------\r\n" .
                    "Meer informatie: " . url('/agenda/' . $agenda_item->id));
            $calendar->addEvent($event);
        }

        $componentFactory = new CalendarFactory();
        $calendarComponent = $componentFactory->createCalendar($calendar);

        // set Http headers
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="cal.ics"');

        echo $calendarComponent;
    }
}
