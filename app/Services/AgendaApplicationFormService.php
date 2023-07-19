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
        $agendaItem->load('getApplicationForm.applicationFormRows', 'getApplicationFormResponses.getApplicationResponseUser', 'getApplicationFormResponses.getApplicationFormResponseRows.getApplicationFormRow');
    
        // Retrieve necessary objects
        $applicationForm = $agendaItem->getApplicationForm;
        $applicationResponses = $agendaItem->getApplicationFormResponses;
    
        // Map custom fields
        $customfields = $applicationForm->applicationFormRows
            ->pluck('applicationFormRowName')
            ->map->text()
            ->all();
    
        // Map user data
        $userdata = $applicationResponses->map(function ($response) use ($agendaItem) {
            $user = $response->getApplicationResponseUser->toArray();
            $user["_signupId"] = $response->id;
            
            $response->getApplicationFormResponseRows->each(function($responseRow) use (&$user) {
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
            "agendaitem"   => $agendaItem->agendaItemTitle->text(),
            "agendaId"     => $agendaItem->id,
            "userdata"     => $userdata,
            "customfields" => $customfields
        ];
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