<?php

return [

    /*
     * The URL of the Mailman server.
     */
    'url' => env('MAIL_MAN_URL', 'https://api.mailman.test'),

    /*
     * The domain name configured for Mailman.
     */
    'domain' => env('MAIL_MAN_DOMAIN', '@esac.nl'),

    /*
     * The credentials used for authenticating with the Mailman server.
     */
    'credentials' => [
        /*
         * The username for Mailman authentication.
         */
        'username' => env('MAIL_MAN_USERNAME'),

        /*
         * The password for Mailman authentication.
         */
        'password' => env('MAIL_MAN_PASSWORD'),
    ],
];
