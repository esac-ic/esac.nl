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
        define('ICAL_FORMAT', 'Ymd\THis\Z');
 
        $eol = "\r\n";
        $icalObject = "BEGIN:VCALENDAR" . $eol .
        "VERSION:2.0"  . $eol .
        "METHOD:PUBLISH" . $eol .
        "NAME:ESAC Agenda" . $eol .
        "X-WR-CALNAME:ESAC Agenda" . $eol .
        "DESCRIPTION:Een overzicht van alle ESAC activiteiten" . $eol .
        "X-WR-CALDESC:Een overzicht van alle ESAC activiteiten" . $eol .
        "PRODID:-//Eindhovense Studenten Alpen Club//Agenda//NL"  . $eol;
       
        // loop over events
        foreach ($agenda_items as $agenda_item) {
            $icalObject .=
            "BEGIN:VEVENT" . $eol .
            "DTSTART:" . date(ICAL_FORMAT, strtotime($agenda_item->startDate)) . $eol .
            "DTEND:" . date(ICAL_FORMAT, strtotime($agenda_item->endDate))  . $eol .
            "DTSTAMP:" . date(ICAL_FORMAT, strtotime($agenda_item->created_at)) . $eol .
            "SUMMARY:" . $agenda_item->agendaItemTitle->text() . $eol .
            "DESCRIPTION:" . strip_tags($agenda_item->agendaItemText->text()) . $eol .
            "UID:" . $agenda_item->id . "@esac.nl" . $eol .
            "STATUS:CONFIRMED" . $eol .
            "LAST-MODIFIED:" . date(ICAL_FORMAT, strtotime($agenda_item->updated_at)) . $eol .
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
