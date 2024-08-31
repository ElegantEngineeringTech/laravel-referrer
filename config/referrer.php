<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Referrer Sources
    |--------------------------------------------------------------------------
    |
    | These are the classes containing the logic to detect the visitor's referrer.
    | You can disable some of them or add as many as you want.
    | No matter how many sources you define, they will all be stored.
    |
    */
    'sources' => [
        \Elegantly\Referrer\Sources\UtmReferrerSource::class,
        \Elegantly\Referrer\Sources\RequestHeaderSource::class,
        \Elegantly\Referrer\Sources\GoogleClickIdSource::class,
        \Elegantly\Referrer\Sources\MetaClickIdSource::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Referrer Drivers
    |--------------------------------------------------------------------------
    |
    | These are the classes containing the logic to store the visitor's referrer.
    | By default, they are all disabled. To enable a driver, add a "key" value.
    | You can also add your own driver if needed.
    | No matter how many drivers you define, they will all store the referrer sources.
    |
    */
    'drivers' => [
        \Elegantly\Referrer\Drivers\ContextDriver::class => [
            'key' => null,
        ],
        \Elegantly\Referrer\Drivers\SessionDriver::class => [
            'key' => null,
        ],
        \Elegantly\Referrer\Drivers\CookieDriver::class => [
            'key' => null,
            /**
             * Lifetime in seconds
             */
            'lifetime' => 60 * 60 * 24 * 365,
        ],
    ],
];
