<?php

namespace App\Services;

use App\Models\AgendaItem;
use Illuminate\Support\Collection;

class AgendaApplicationFormService
{
    public function getRegisteredUsers(AgendaItem $agendaItem): array
    {
        // Eager load necessary relationships
        $agendaItem->load('getApplicationFormResponses.user');

        // Retrieve necessary objects
        $applicationForm = $agendaItem->getApplicationForm;
        // Anonymised registrations still count, but have no one to list
        $applicationResponses = $agendaItem->getApplicationFormResponses->whereNotNull('user_id');

        if (is_null($applicationForm)) {
            return [
                'agendaItemTitle' => $agendaItem->title,
                'agendaItemId' => $agendaItem->id,
                'userdata' => [],
            ];
        }

        // Map user data
        $userdata = $applicationResponses->map(function ($response) use ($agendaItem) {
            $user = $response->user;
            $user['_signupId'] = $response->id;

            if ($agendaItem->climbing_activity) {
                $user['certificate_names'] = $user->getCertificationsAbbreviations();
            }

            return $user;
        })->all();

        // Build the final array to return
        return [
            'agendaItemTitle' => $agendaItem->title,
            'agendaItemId' => $agendaItem->id,
            'userdata' => $userdata,
        ];
    }

    public function getRegisteredUsersWithResponses(AgendaItem $agendaItem): array
    {
        // Eager load necessary relationships
        $agendaItem->load('getApplicationForm.applicationFormRows', 'getApplicationFormResponses.user', 'getApplicationFormResponses.getApplicationFormResponseRows.getApplicationFormRow');

        // Retrieve necessary objects
        $applicationForm = $agendaItem->getApplicationForm;
        // Anonymised registrations still count, but have no one to list
        $applicationResponses = $agendaItem->getApplicationFormResponses->whereNotNull('user_id');

        if (is_null($applicationForm)) {
            return [
                'agendaitem' => $agendaItem->title,
                'agendaId' => $agendaItem->id,
                'userdata' => [],
                'customfields' => [],
            ];
        }

        // Map custom fields
        $customfields = $applicationForm->applicationFormRows
            ->pluck('name')
            ->all();

        // Map user data
        $userdata = $applicationResponses->map(function ($response) use ($agendaItem) {
            $user = $response->user;
            $user['_signupId'] = $response->id;

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
            'agendaitem' => $agendaItem->title,
            'agendaId' => $agendaItem->id,
            'userdata' => $userdata,
            'customfields' => $customfields,
        ];
    }

    public function getRegisteredUserIds(AgendaItem $agendaItem): array
    {
        // This method now only returns user IDs
        $applicationResponses = $agendaItem->getApplicationFormResponses->whereNotNull('user_id');

        // Map user data
        $userdataIds = $applicationResponses->map(function ($response) {
            $user = $response->user;

            return $user->id;
        })->all();

        return $userdataIds;
    }

    public function getExportData(AgendaItem $agendaItem): Collection
    {
        $users = $this->getRegisteredUsersWithResponses($agendaItem);
        $selectedElements = [
            'firstname',
            'preposition',
            'lastname',
            'street',
            'house_number',
            'city',
            'zipcode',
            'email',
            'phone_number',
            'birthdayFormatted',
        ];
        $selectedElements = array_merge($selectedElements, $users['customfields']);

        $activeUsers = [];
        foreach ($users['userdata'] as $user) {
            $userline = [];
            foreach ($selectedElements as $element) {
                $userline[$element] = $user[$element];
            }

            array_push($activeUsers, $userline);
        }

        return new Collection($activeUsers);
    }
}
