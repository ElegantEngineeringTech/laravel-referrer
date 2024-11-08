<?php

use Elegantly\Referrer\Enums\Strategy;

return [
    /*
    |--------------------------------------------------------------------------
    | Referrer Sources
    |--------------------------------------------------------------------------
    |
    | These classes contain the logic to detect the visitor's referrer.
    | You can disable specific sources or add as many as needed.
    | Regardless of the number of sources defined, all values will be stored.
    |
    */
    'sources' => [
        \Elegantly\Referrer\Sources\UtmReferrerSource::class,
        \Elegantly\Referrer\Sources\RequestHeaderSource::class,
        \Elegantly\Referrer\Sources\GoogleClickIdSource::class,
        \Elegantly\Referrer\Sources\MetaClickIdSource::class,
        \Elegantly\Referrer\Sources\TikTokClickIdSource::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Referrer Strategy
    |--------------------------------------------------------------------------
    |
    | Define the strategy for storing referrer values. You can also customize
    | the strategy for each source.
    |
    | 'last'  : Stores only the last captured value, ignoring previous ones.
    | 'first' : Stores only the first captured value, ignoring subsequent ones.
    | 'all'   : Stores all captured values.
    |
    */
    'strategy' => Strategy::All,

    /*
    |--------------------------------------------------------------------------
    | Referrer Drivers
    |--------------------------------------------------------------------------
    |
    | These classes contain the logic to store the visitor's referrer.
    | By default, all drivers are disabled. To enable a driver, uncomment it
    | or add a custom driver.
    |
    | Regardless of the number of drivers defined, they will all store
    | referrer sources. When accessing referrer data, drivers may be merged,
    | with the last driver overwriting the others.
    |
    | It is recommended to enable at least the Cookie driver for long-term
    | storage and the Session driver for immediate storage.
    |
    */
    'drivers' => [
        // \Elegantly\Referrer\Drivers\CookieDriver::class => [
        //     'name' => Str::slug(env('APP_NAME', 'laravel'), '_') . '_referrer',
        //     /**
        //      * Lifetime in seconds.
        //      */
        //     'lifetime' => 60 * 60 * 24 * 365,
        // ],
        // \Elegantly\Referrer\Drivers\SessionDriver::class => [
        //     'key' => 'referrer',
        // ],
        // \Elegantly\Referrer\Drivers\ContextDriver::class => [
        //     'key' => 'referrer',
        // ],
    ],
];
