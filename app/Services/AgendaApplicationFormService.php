<?php

namespace App\Services;

use App\AgendaItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AgendaApplicationFormService
{
    /**
     * @param AgendaItem $agendaItem
     * @return array
     */
    public function getRegisteredUsers(AgendaItem $agendaItem): array
    {
        // Eager load necessary relationships
        $agendaItem->load('getApplicationForm.applicationFormRows', 'getApplicationFormResponses.getApplicationResponseUser', 'getApplicationFormResponses.getApplicationFormResponseRows.getApplicationFormRow');

        // Here, add logging to check if $agendaItem is loaded correctly and if it has associated application form.
        if ($agendaItem === null) {
            Log::error('AgendaItem is null', ['agendaItem' => $agendaItem]);
        } else {
            Log::info('AgendaItem loaded', ['agendaItem' => $agendaItem]);
        }

        // Retrieve necessary objects
        $applicationForm = $agendaItem->getApplicationForm;

        // Add logging to check if $applicationForm is null
        if ($applicationForm === null) {
            Log::error('ApplicationForm is null', ['agendaItemId' => $agendaItem->id]);
            // Consider returning an empty array or throw an exception here, as the rest of the code assumes $applicationForm is not null
            return [];
        } else {
            Log::info('ApplicationForm loaded', ['applicationForm' => $applicationForm]);
        }

        $applicationResponses = $agendaItem->getApplicationFormResponses;

        // Map custom fields
        $customfields = $applicationForm->applicationFormRows
            ->pluck('applicationFormRowName')
            ->map->text()
            ->all();

        // Map user data
        $userdata = $applicationResponses->map(function ($response) use ($agendaItem) {
            $user = $response->getApplicationResponseUser;
            $user["_signupId"] = $response->id;

            $response->getApplicationFormResponseRows->each(function ($responseRow) use (&$user) {
                $columnname = $responseRow->getApplicationFormRow->applicationFormRowName->text();
                $user[$columnname] = $responseRow->value;
            });

            if ($agendaItem->climbing_activity) {
                $user['certificate_names'] = $user->getCertificationsAbbreviations();
            }
            return $user;
        })->all();

        // Build the final array to return
        return [
            "agendaitem" => $agendaItem->agendaItemTitle->text(),
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
