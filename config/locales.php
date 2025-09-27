<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Available Locales
    |--------------------------------------------------------------------------
    |
    | Define the locales available in the application. The array keys are the
    | locale short codes used by the application (e.g. 'ar', 'en'). Each entry
    | may include a human friendly name and direction to help the UI.
    |
    */

    'available' => [
        'ar' => [
            'name' => 'العربية',
            'dir' => 'rtl',
            'locale' => 'ar_EG',
        ],
        'en' => [
            'name' => 'English',
            'dir' => 'ltr',
            'locale' => 'en_US',
        ],
    ],

    'aliases' => [
        'ar_EG' => 'ar',
        'ar-eg' => 'ar',
        'en_US' => 'en',
        'en-us' => 'en',
    ],

    'default' => env('APP_LOCALE', 'ar_EG'),
];
