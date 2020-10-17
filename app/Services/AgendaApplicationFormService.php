<?php

namespace App\Services;

use App\AgendaItem;
use Illuminate\Support\Collection;

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
        foreach ($applicationForm->applicationFormRows as $application_row) {
            array_push($userdata["customfields"], $application_row->applicationFormRowName->text());
        }

        $applicationResponses = $agendaItem->getApplicationFormResponses;
        foreach ($applicationResponses as $index => $response) {
            $user                    = $response->getApplicationResponseUser;
            $applicationResponseRows = $response->getApplicationFormResponseRows;
            foreach ($applicationResponseRows as $responseRow) {
                $columnname        = $responseRow->getApplicationFormRow->applicationFormRowName->text();
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

    /**
     * @param AgendaItem $agendaItem
     * @return Collection
     */
    public function getExportData(AgendaItem $agendaItem): Collection
    {
        $users            = $this->getRegisteredUsers($agendaItem);
        $selectedElements = array(
            "firstname",
            "preposition",
            "lastname",
            "street",
            "houseNumber",
            "city",
            "email",
            "phonenumber"
        );
        $selectedElements = array_merge($selectedElements, $users["customfields"]);

        $activeUsers = [];
        foreach ($users["userdata"] as $user) {
            $userline = array();
            foreach ($selectedElements as $element) {
                $userline[$element] = $user[$element];
            }
            array_push($activeUsers, $userline);
        }

        return new Collection($activeUsers);
    }

}