<?php

namespace App\Services;

use App\AgendaItem;

class AgendaApplicationFormService
{
    /**
     * @param AgendaItem $agendaItem
     * @return array
     */
    public function getRegisteredUsers(AgendaItem $agendaItem): array
    {
        $userdata = array(
            "agendaitem"   => $agendaItem->agendaItemTitle->text(),
            "userdata"     => array(),
            "agendaId"     => $agendaItem->id,
            "customfields" => array()
        );

        $applicationForm = $agendaItem->getApplicationForm;
        foreach ($applicationForm->applicationFormRows() as $application_row) {
            array_push($userdata["customfields"], $application_row->applicationFormRowName->text());
        }

        $applicationResponses = $agendaItem->getApplicationFormResponses;
        foreach ($applicationResponses as $index => $response) {
            $user                    = $response->getApplicationResponseUser;
            $applicationResponseRows = $response->getApplicationFormResponseRows;
            foreach ($applicationResponseRows as $responseRow) {
                $columnname        = $responseRow->getApplicationFormResponseRowName->applicationFormRowName->text();
                $user[$columnname] = $responseRow->value;
            }

            //Adding the signup id in the fields
            $user["_signupId"] = $response->id;
            if (true === $agendaItem->climbing_activity) {
                $user['certificate_names'] = $user->getCertificationsAbbreviations();
            }
            array_push($userdata["userdata"], $user);

        }
        return $userdata;
    }
}