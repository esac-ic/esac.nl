<?php

namespace App\Services;

use App\Models\AgendaItem;
use App\Models\User;
use Illuminate\Support\Collection;

class AgendaApplicationFormService
{
    public function getRegisteredUsers(AgendaItem $agendaItem): array
    {
        // Eager load necessary relationships
        $agendaItem->load('registrations.user');

        // Retrieve necessary objects
        $applicationForm = $agendaItem->applicationForm;
        // Anonymised registrations still count, but have no one to list
        $applicationResponses = $agendaItem->registrations->whereNotNull('user_id');

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
        $agendaItem->load('applicationForm.applicationFormRows', 'registrations.user', 'registrations.rows.formRow');

        // Retrieve necessary objects
        $applicationForm = $agendaItem->applicationForm;
        // Anonymised registrations still count, but have no one to list
        $applicationResponses = $agendaItem->registrations->whereNotNull('user_id');

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

            $response->rows->each(function ($responseRow) use (&$user) {
                $columnname = $responseRow->formRow->name;
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

        return collect($users['userdata'])->map(fn (User $user) => $user->only($selectedElements));
    }
}
