<?php

return [

    'mailman' => [
        'url' => env('MAILMAN_URL'),
        'list_domain' => env('MAILMAN_LIST_DOMAIN', '@esac.nl'),
        'username' => env('MAILMAN_USERNAME'),
        'password' => env('MAILMAN_PASSWORD'),
    ],

    'recaptcha' => [
        'key' => env('GOOGLE_RECAPTCHA_KEY'),
        'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
    ],

];
