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
                new EmailDomainValidator,
            ],
            'firstname' => ['required', 'max:255'],
            'preposition' => ['nullable', 'max:255'],
            'lastname' => ['required', 'max:255'],
            'street' => ['required', 'max:255'],
            'house_number' => ['required', 'max:255'],
            'city' => ['required', 'max:255'],
            'zipcode' => ['required', 'max:255'],
            'country' => ['required', 'max:255'],
            'phone_number' => ['required', 'max:255'],
            'phone_number_alt' => ['nullable', 'max:255'],
            'emergency_number' => ['required', 'max:255'],
            'emergency_house_number' => ['required', 'max:255'],
            'emergency_street' => ['required', 'max:255'],
            'emergency_city' => ['required', 'max:255'],
            'emergency_zipcode' => ['required', 'max:255'],
            'emergency_country' => ['required', 'max:255'],
            'birthday' => ['required', 'date'],
            'iban' => ['required', 'max:255'],
            'bic' => ['nullable', 'max:255'],
        ];
    }
}
