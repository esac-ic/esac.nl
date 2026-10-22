<?php

namespace App\Http\Controllers;

use App\Models\AgendaItem;
use DateTimeImmutable;
use DateTimeZone as PhpDateTimeZone;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\Entity\TimeZone;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Illuminate\Http\Response;

class ICalController extends Controller
{
    public function getAgendaItemsICalObject(): Response
    {
        $phpDateTimeZone = new PhpDateTimeZone('Europe/Amsterdam');
        $timeZone = TimeZone::createFromPhpDateTimeZone(
            $phpDateTimeZone,
            new DateTimeImmutable('-1 year midnight', $phpDateTimeZone),
            new DateTimeImmutable('+1 year midnight', $phpDateTimeZone),
        );

        $calendar = new Calendar([]);
        $calendar->addTimeZone($timeZone);
        $host = parse_url(config('app.url'), PHP_URL_HOST);

        foreach (AgendaItem::all() as $agendaItem) {
            $event = new Event(new UniqueIdentifier('agenda-item-'.$agendaItem->id.'@'.$host));
            $event
                ->setOccurrence(new TimeSpan(
                    new DateTime(new DateTimeImmutable($agendaItem->start_date), false),
                    new DateTime(new DateTimeImmutable($agendaItem->end_date), false),
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
