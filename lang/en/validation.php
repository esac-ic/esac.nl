<?php

return [
    'attributes' => [
        'privacy_policy' => 'Privacy Policy',
        'terms_and_conditions' => 'Terms and Conditions',
        'direct_debit' => 'direct debit',
        'iban' => 'IBAN',
        'bic' => 'BIC',
        'zipcode' => 'postal code',
        'birthday' => 'date of birth',
        'emergency_number' => 'emergency phone number',
        'emergency_street' => 'emergency address street',
        'emergency_house_number' => 'emergency address house number',
        'emergency_zipcode' => 'emergency postal code',
    ],
    'custom' => [
        'email' => [
            'doesnt_end_with' => 'Email addresses ending in :values can not be used, so that you keep receiving our emails after you graduate.',
        ],
        'g-recaptcha-response' => [
            'required' => "Please tick 'I'm not a robot'.",
        ],
    ],
];
