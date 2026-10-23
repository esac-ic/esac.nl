<?php

return [
    'unique' => 'The :attribute is already used',
    'min' => [
        'numeric' => ':attribute has to be at least :min',
    ],
    'required' => 'Field :attribute is required',
    'attributes' => [
        'privacy_policy' => 'Privacy Policy',
        'terms_and_conditions' => 'Terms and Conditions',
        'direct_debit' => 'direct debit',
        'iban' => 'IBAN',
        'bic' => 'BIC',
    ],
    'custom' => [
        'email' => [
            'doesnt_end_with' => 'Email addresses ending in :values can not be used, so that you keep receiving our emails after you graduate',
        ],
        'g-recaptcha-response' => [
            'required' => "'I'm not a robot' validation is required",
        ],
    ],
];
