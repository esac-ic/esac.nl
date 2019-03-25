<?php

namespace App\Http\Controllers;

use App\AgendaItem;
use Illuminate\Http\Request;
use App\repositories\AgendaItemRepository;
use App\repositories\RepositorieFactory;
use Illuminate\Support\Facades\App;

class ICalController extends Controller
{
    private $_agendaRepository;

    public function __construct(RepositorieFactory $repositorieFactory)
    {
        $this->_agendaRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$AGENDAITEMREPOKEY);
    }

    public function getAgendaItemsICalObject()
    {
        $agenda_items = AgendaItem::all();
        define('ICAL_FORMAT', 'Ymd\THis\Z');
 
        $icalObject = "BEGIN:VCALENDAR
        VERSION:2.0
        METHOD:PUBLISH
        PRODID:-//Eindhovense Studenten Alpen Club//Agenda//NL\n";
       
        // loop over events
        foreach ($agenda_items as $agenda_item) {
            $icalObject .=
            "BEGIN:VEVENT
            DTSTART:" . date(ICAL_FORMAT, strtotime($agenda_item->startDate)) . "
            DTEND:" . date(ICAL_FORMAT, strtotime($agenda_item->endDate)) . "
            DTSTAMP:" . date(ICAL_FORMAT, strtotime($agenda_item->created_at)) . "
            SUMMARY:123 
            UID:$agenda_item->id
            STATUS:CONFIRMED
            LAST-MODIFIED:" . date(ICAL_FORMAT, strtotime($agenda_item->updated_at)) . "
            END:VEVENT\n";
        }
 
        // close calendar
        $icalObject .= "END:VCALENDAR";
 
        // Set the headers
        header('Content-type: text/calendar');
        header('Content-Disposition: attachment; filename="cal.ics"');
       
        $icalObject = str_replace(' ', '', $icalObject);
   
        echo $icalObject;
    }
}
