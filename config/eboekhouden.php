<?php

return [
    /*
     * The URL of eboekhouden.
     */
    'url' => env('EBOEKHOUDEN_URL', 'https://api.e-boekhouden.nl'),
    /*
     * The API token of eboekhouden.
     */
    'api_token' => env('EBOEKHOUDEN_API_TOKEN', ''),
    /*
     * An identifier for the support team of eboekhouden to identify the requests.
     * Must be a maximum of 10 characters and match the following regex: ^[\w_ ]{1,10}$
     */
    'source' => env('EBOEKHOUDEN_SOURCE', 'esac_web'),
];