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
        $vCalendar = new \Eluceo\iCal\Component\Calendar('//Eindhovense Studenten Alpen Club//Agenda//NL');

        foreach ($agenda_items as $agenda_item) {
            $vEvent = new \Eluceo\iCal\Component\Event();
            $vEvent
                ->setDtStart(new \DateTime($agenda_item->startDate))
                ->setDtEnd(new \DateTime($agenda_item->endDate))
                ->setSummary($agenda_item->agendaItemTitle->text())
                ->setDescription(strip_tags($agenda_item->agendaItemText->text()));
                // TODO: meer info in beschrijving toevoegen zoals inschrijf url of meer info url?
            $vCalendar->addComponent($vEvent);
        }

        // Set the headers
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="cal.ics"');

        echo $vCalendar->render();
    }
}
