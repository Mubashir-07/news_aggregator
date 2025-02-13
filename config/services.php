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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'news_api' => [
        'key' => 'a709ea6fe90e45ad934fbb54d39ad9ed',
        'base_url' => 'https://newsapi.org/v2/'
    ],

    'the_guardian' => [
        'key' => '62a95103-7fdc-477e-b936-b6d2d7ae021d',
        'base_url' => 'https://content.guardianapis.com/'
    ],

    'nyt' => [
        'key' => 'cm5tjhUzNkktslHaZsGokMHhwQkh0ZNd',
        'base_url' => 'https://api.nytimes.com/svc'
    ],
];
