<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Territory
    |--------------------------------------------------------------------------
    |
    | The default UK territory to use when no territory is specified.
    | Options: 'england-and-wales', 'scotland', 'northern-ireland'
    |
    */
    'default_territory' => env('UK_BANK_HOLIDAY_TERRITORY', 'england-and-wales'),

    /*
    |--------------------------------------------------------------------------
    | Cache Duration
    |--------------------------------------------------------------------------
    |
    | How long to cache the GOV.UK API response (in seconds).
    | Default: 24 hours (86400 seconds)
    |
    */
    'cache_duration' => env('UK_BANK_HOLIDAY_CACHE', 86400),

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | Prefix for cache keys to avoid collisions with other cached data.
    |
    */
    'cache_key_prefix' => 'uk-bank-holidays',

    /*
    |--------------------------------------------------------------------------
    | GOV.UK API URL
    |--------------------------------------------------------------------------
    |
    | The official UK government bank holidays JSON feed.
    | Can be overridden for testing purposes.
    |
    */
    'api_url' => env('UK_BANK_HOLIDAY_API', 'https://www.gov.uk/bank-holidays.json'),
];
