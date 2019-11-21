<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => Marketplace\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],


    'facebook' => [
        'client_id' => '2535196466503588',
        'client_secret' => '12626cb4aadb93decf7c2038d2a2eea5',
        'redirect' => env('APP_URL').'/api/v2/authenticate/provider/facebook/callback',
    ],


    'twitter' => [
        'client_id' => '#',
        'client_secret' => '#',
        'redirect' => env('APP_URL').'/twitter/callback',
    ],

    'google' => [
        'client_id' => '28552471099-2a968gr8kprif5ot1musqa2q9418h79t.apps.googleusercontent.com',
        'client_secret' => 'Bm4tD6zjmkAS2FIsE9cBMAUi',
        'redirect' => env('APP_URL').'/api/v2/authenticate/provider/google/callback',
    ],

];