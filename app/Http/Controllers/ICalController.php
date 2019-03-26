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

        // Set default timezone
        $tz = 'Europe/Amsterdam';
        $dtz = new \DateTimeZone($tz);
        date_default_timezone_set($tz);

        // Create new calendar
        $vCalendar = new \Eluceo\iCal\Component\Calendar('ESAC Agenda');
        $vCalendar
            ->setName('ESAC Agenda - esac.nl')
            ->setDescription("ESAC Agenda - esac.nl")
            ->setCalendarColor('yellow');

        // Create timezone rule object for Daylight Saving Time
        $vTimezoneRuleDst = new \Eluceo\iCal\Component\TimezoneRule(\Eluceo\iCal\Component\TimezoneRule::TYPE_DAYLIGHT);
        $vTimezoneRuleDst->setTzName('CEST');
        $vTimezoneRuleDst->setDtStart(new \DateTime('1981-03-29 02:00:00', $dtz));
        $vTimezoneRuleDst->setTzOffsetFrom('+0100');
        $vTimezoneRuleDst->setTzOffsetTo('+0200');
        $dstRecurrenceRule = new \Eluceo\iCal\Property\Event\RecurrenceRule();
        $dstRecurrenceRule->setFreq(\Eluceo\iCal\Property\Event\RecurrenceRule::FREQ_YEARLY);
        $dstRecurrenceRule->setByMonth(3);
        $dstRecurrenceRule->setByDay('-1SU');
        $vTimezoneRuleDst->setRecurrenceRule($dstRecurrenceRule);

        // Create timezone rule object for Standard Time
        $vTimezoneRuleStd = new \Eluceo\iCal\Component\TimezoneRule(\Eluceo\iCal\Component\TimezoneRule::TYPE_STANDARD);
        $vTimezoneRuleStd->setTzName('CET');
        $vTimezoneRuleStd->setDtStart(new \DateTime('1996-10-27 03:00:00', $dtz));
        $vTimezoneRuleStd->setTzOffsetFrom('+0200');
        $vTimezoneRuleStd->setTzOffsetTo('+0100');
        $stdRecurrenceRule = new \Eluceo\iCal\Property\Event\RecurrenceRule();
        $stdRecurrenceRule->setFreq(\Eluceo\iCal\Property\Event\RecurrenceRule::FREQ_YEARLY);
        $stdRecurrenceRule->setByMonth(10);
        $stdRecurrenceRule->setByDay('-1SU');
        $vTimezoneRuleStd->setRecurrenceRule($stdRecurrenceRule);

        // Create timezone definition and add rules
        $vTimezone = new \Eluceo\iCal\Component\Timezone($tz);
        $vTimezone->addComponent($vTimezoneRuleDst);
        $vTimezone->addComponent($vTimezoneRuleStd);
        $vCalendar->setTimezone($vTimezone);

        foreach ($agenda_items as $agenda_item) {
            $vEvent = new \Eluceo\iCal\Component\Event();
            $vEvent
                ->setDtStart(new \DateTime($agenda_item->startDate))
                ->setDtEnd(new \DateTime($agenda_item->endDate))
                ->setSummary($agenda_item->agendaItemTitle->text())
                ->setDescription(
                    html_entity_decode(strip_tags($agenda_item->agendaItemText->text())) .
                    "\r\n\r\n----------\r\n" .
                    "Meer informatie: " . url('/agenda/' . $agenda_item->id));
            $vCalendar->addComponent($vEvent);
        }

        // Set the headers
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="cal.ics"');

        echo $vCalendar->render();
    }
}
