<?php

namespace App\Http\Requests;

use App\Models\AgendaItem;
use Illuminate\Validation\Rule;

/**
 * An admin registering a current member, who isn't registered yet, with their answers.
 */
class AdminRegistrationRequest extends RegistrationRequest
{
    public function rules(): array
    {
        // + keeps the numeric row id keys, which a spread would renumber.
        return parent::rules() + [
            'user' => [
                'required',
                Rule::exists('users', 'id')->whereNull('membership_ended_on')->whereNull('pending_since')->withoutTrashed(),
                Rule::unique('application_responses', 'user_id')->where('agenda_item_id', $this->agendaItem()->id)->withoutTrashed(),
            ],
        ];
    }

    protected function agendaItem(): AgendaItem
    {
        return $this->route('agenda_item');
    }
}
