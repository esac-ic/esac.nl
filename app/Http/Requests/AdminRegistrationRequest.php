<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

/**
 * An admin registering a current member, who isn't registered yet, with their answers.
 */
class AdminRegistrationRequest extends RegistrationRequest
{
    public function rules(): array
    {
        // + keeps the numeric field id keys, which a spread would renumber.
        return parent::rules() + [
            'user' => [
                'required',
                Rule::exists('users', 'id')->whereNull('membership_ended_on')->whereNull('pending_since')->withoutTrashed(),
                Rule::unique('registrations', 'user_id')->where('event_id', $this->event()->id)->withoutTrashed(),
            ],
        ];
    }
}
