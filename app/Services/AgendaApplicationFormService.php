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
    public function getRegisteredUsersWithResponses(AgendaItem $agendaItem): array
    {
        // Eager load necessary relationships
        $agendaItem->load('getApplicationForm.applicationFormRows', 'getApplicationFormResponses.getApplicationResponseUser', 'getApplicationFormResponses.getApplicationFormResponseRows.getApplicationFormRow');

        // Retrieve necessary objects
        $applicationForm = $agendaItem->getApplicationForm;
        $applicationResponses = $agendaItem->getApplicationFormResponses;

        if (is_null($applicationForm)) {
            return [
                "agendaitem" => $agendaItem->title,
                "agendaId" => $agendaItem->id,
                "userdata" => [],
                "customfields" => [],
            ];
        }

        // Map custom fields
        $customfields = $applicationForm->applicationFormRows
            ->pluck('name')
            ->all();

        // Map user data
        $userdata = $applicationResponses->map(function ($response) use ($agendaItem) {
            $user = $response->getApplicationResponseUser;
            $user["_signupId"] = $response->id;

            $response->getApplicationFormResponseRows->each(function ($responseRow) use (&$user) {
                $columnname = $responseRow->getApplicationFormRow->name;
                $user[$columnname] = $responseRow->value;
            });

            if ($agendaItem->climbing_activity) {
                $user['certificate_names'] = $user->getCertificationsAbbreviations();
            }
            return $user;
        })->all();

        // Build the final array to return
        return [
            "agendaitem" => $agendaItem->title,
            "agendaId" => $agendaItem->id,
            "userdata" => $userdata,
            "customfields" => $customfields,
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
        $users = $this->getRegisteredUsersWithResponses($agendaItem);
        $selectedElements = array(
            "firstname",
            "preposition",
            "lastname",
            "street",
            "houseNumber",
            "city",
            "zipcode",
            "email",
            "phonenumber",
            "birthDayFormatted",
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
