<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'stripe' => [
        'secret' => 'sk_test_51IdtHCGmNbFgnn002634Wne6qPdy0KfZyH19qLIq7SCFxNdWygtpxUc0d9VFQs55dlWs2sAp5O565RdTYfMpe0Op00fMGUCof1',
        'publish' => 'pk_test_51IdtHCGmNbFgnn00GS9N3SgfZldmDiOvK5WbKahPhImD2ThfzRqUKTMYG3i4xwTcphNBUb9FfeQFmBK37t3h4Ewh00JnMUB9Ul',
    ]

];
