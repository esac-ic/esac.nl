<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 5-12-2016
 * Time: 19:29
 */
return [
    "unique"        => "The :attribute  is already used",
    "Unauthorized" => "You do not have sufficient access to view this page",
    "PageNotFound" => "Page not found",
    "min" => [
        "numeric"   => ":attribute has to be at least :min"
    ],
    "oldUserLogin" => "Old members can not login",
    "pendingUserLogin" => "Pending members can not login",
    "required" => "Field :attribute is required",
    "date" => "Invalid birth date",
    "error" => "Error",
    'bannedEmailDomain' => "Email addresses of educational institutions (student.tue.nl) are not allowed to make sure you can still receive our emails after you graduate",
    'fileToLarge' => 'Files is to large. The max size is 20mb',
    'attributes' => [
        'privacy_policy' => 'Privacy Policy',
        'termsconditions' => 'Terms and Conditions',
        'incasso' => 'Automatic Collection',
    ],
    'custom' => [
        'amount_of_formrows' => [
            'min' => 'The minimum amount of rows is 1',
        ],
        'g-recaptcha-response' => [
            "required" => "'I'm not a robot' validation is required",
        ]
    ]
];
