<?php

namespace App\Http\Requests\Concerns;

use App\Rules\EmailDomainValidator;
use Illuminate\Validation\Rule;

trait ValidatesMemberDetails
{
    /**
     * The rules for the personal details every member fills in.
     */
    protected function memberDetailRules(?int $ignoreUserId = null): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($ignoreUserId),
                new EmailDomainValidator(),
            ],
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'street' => 'required|max:255',
            'houseNumber' => 'required|max:255',
            'city' => 'required|max:255',
            'zipcode' => 'required|max:255',
            'country' => 'required|max:255',
            'phonenumber' => 'required|max:255',
            'emergencyNumber' => 'required|max:255',
            'emergencyHouseNumber' => 'required|max:255',
            'emergencystreet' => 'required|max:255',
            'emergencycity' => 'required|max:255',
            'emergencyzipcode' => 'required|max:255',
            'emergencycountry' => 'required|max:255',
            'birthDay' => 'required|date',
            'IBAN' => 'required|max:255',
        ];
    }
}
