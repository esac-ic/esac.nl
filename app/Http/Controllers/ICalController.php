<?php

namespace App\Http\Controllers;

use App\AgendaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ICalController extends Controller
{
    public function getAgendaItemsICalObject()
    {
        $agenda_items = AgendaItem::all();
        define('ICAL_FORMAT', 'Ymd\THis');
 
        $eol = "\r\n";
        $icalObject = "BEGIN:VCALENDAR" . $eol .
        "VERSION:2.0"  . $eol .
        "METHOD:PUBLISH" . $eol .
        "NAME:ESAC Agenda" . $eol .
        "X-WR-CALNAME:ESAC Agenda" . $eol .
        "DESCRIPTION:Een overzicht van alle ESAC activiteiten" . $eol .
        "X-WR-CALDESC:Een overzicht van alle ESAC activiteiten" . $eol .
        "PRODID:-//Eindhovense Studenten Alpen Club//Agenda//NL"  . $eol .
        "BEGIN:VTIMEZONE" . $eol .
        "TZID:Europe/Amsterdam" . $eol .
        "BEGIN:DAYLIGHT" . $eol .
        "TZOFFSETFROM:+0100" . $eol .
        "TZOFFSETTO:+0200" . $eol .
        "DTSTART:19810329T020000" . $eol .
        "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU" . $eol .
        "TZNAME:CEST" . $eol .
        "END:DAYLIGHT" . $eol .
        "BEGIN:STANDARD" . $eol .
        "TZOFFSETFROM:+0200" . $eol .
        "TZOFFSETTO:+0100" . $eol .
        "DTSTART:19961027T030000" . $eol .
        "RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU" . $eol .
        "TZNAME:CET" . $eol .
        "END:STANDARD" . $eol .
        "END:VTIMEZONE" . $eol;
       
        // loop over events
        foreach ($agenda_items as $agenda_item) {
            $icalObject .=
            "BEGIN:VEVENT" . $eol .
            "DTSTART;TZID=Europe/Amsterdam:" . date(ICAL_FORMAT, strtotime($agenda_item->startDate)) . $eol .
            "DTEND;TZID=Europe/Amsterdam:" . date(ICAL_FORMAT, strtotime($agenda_item->endDate))  . $eol .
            "DTSTAMP;TZID=Europe/Amsterdam:" . date(ICAL_FORMAT, strtotime($agenda_item->created_at)) . $eol .
            "SUMMARY:" . $agenda_item->agendaItemTitle->text() . $eol .
            "DESCRIPTION:" . strip_tags($agenda_item->agendaItemText->text()) . $eol .
            "UID:" . $agenda_item->id . "@esac.nl" . $eol .
            "STATUS:CONFIRMED" . $eol .
            "LAST-MODIFIED;TZID=Europe/Amsterdam:" . date(ICAL_FORMAT, strtotime($agenda_item->updated_at)) . $eol .
            "END:VEVENT" . $eol;
        }
 
        // close calendar
        $icalObject .= "END:VCALENDAR";
 
        // Set the headers
        header('Content-type: text/calendar; charset=utf-8’');
        header('Content-Disposition: attachment; filename="cal.ics"');
          
        echo $icalObject;
    }
}
