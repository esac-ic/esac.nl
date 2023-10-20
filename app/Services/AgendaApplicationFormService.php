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
        // Eager load necessary relationships
        $agendaItem->load('getApplicationFormResponses.getApplicationResponseUser');

        // Retrieve necessary objects
        $applicationForm = $agendaItem->getApplicationForm;
        $applicationResponses = $agendaItem->getApplicationFormResponses;

        if (is_null($applicationForm)) {
            return [
                "agendaItemTitle" => $agendaItem->title,
                "agendaItemId" => $agendaItem->id,
                "userdata" => [],
            ];
        }

        // Map user data
        $userdata = $applicationResponses->map(function ($response) use ($agendaItem) {
            $user = $response->getApplicationResponseUser;
            $user["_signupId"] = $response->id;

            if ($agendaItem->climbing_activity) {
                $user['certificate_names'] = $user->getCertificationsAbbreviations();
            }
            return $user;
        })->all();

        // Build the final array to return
        return [
            "agendaItemTitle" => $agendaItem->title,
            "agendaItemId" => $agendaItem->id,
            "userdata" => $userdata,
        ];
    }

    /**
     * @param AgendaItem $agendaItem
     * @return array
     */
    public function getRegisteredUserIds(AgendaItem $agendaItem): array
    {
        // This method now only returns user IDs
        $applicationResponses = $agendaItem->getApplicationFormResponses;

        // Map user data
        $userdataIds = $applicationResponses->map(function ($response) {
            $user = $response->getApplicationResponseUser;
            return $user->id;
        })->all();

        return $userdataIds;
    }

    /**
     * @param AgendaItem $agendaItem
     * @return Collection
     */
    public function getExportData(AgendaItem $agendaItem): Collection
    {
        $users = $this->getRegisteredUsers($agendaItem);
        $selectedElements = array(
            "firstname",
            "preposition",
            "lastname",
            "street",
            "houseNumber",
            "city",
            "email",
            "phonenumber",
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
