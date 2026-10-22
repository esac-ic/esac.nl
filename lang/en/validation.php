<?php

return [
    'unique' => 'The :attribute  is already used',
    'min' => [
        'numeric' => ':attribute has to be at least :min',
    ],
    'required' => 'Field :attribute is required',
    'attributes' => [
        'privacy_policy' => 'Privacy Policy',
        'termsconditions' => 'Terms and Conditions',
        'incasso' => 'Automatic Collection',
        'iban' => 'IBAN',
        'bic' => 'BIC',
    ],
    'custom' => [
        'g-recaptcha-response' => [
            'required' => "'I'm not a robot' validation is required",
        ],
    ],
];
