<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Save periods for AVG script
    |--------------------------------------------------------------------------
    */
    'old_users_save_period' => env('OLD_USER_SAVE_PERIODE', 2),
    'application_response_save_period' => env('APPLICATION_RESPONSE_SAVE_PERIODE', 2),

    /*
    |--------------------------------------------------------------------------
    | Google ReCaptcha
    |--------------------------------------------------------------------------
    */
    'google_recaptcha_key' => env('GOOGLE_RECAPTCHA_KEY'),
    'google_recaptcha_secret' => env('GOOGLE_RECAPTCHA_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Backblaze B2
    |--------------------------------------------------------------------------
    */
    'b2_storage_url' => env('B2_STORAGE_URL'),
    'b2_bucketname' => env('B2_BUCKETNAME'),
    'b2_account_key_id' => env('B2_ACCOUNT_KEY_ID'),
    'b2_application_key' => env('B2_APPLICATION_KEY'),

];