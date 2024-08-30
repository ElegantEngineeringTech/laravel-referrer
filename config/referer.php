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
        \Elegantly\Referer\Sources\UtmRefererSource::class,
        \Elegantly\Referer\Sources\RequestHeaderSource::class,
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
        \Elegantly\Referer\Drivers\ContextDriver::class => [
            'key' => null,
        ],
        \Elegantly\Referer\Drivers\SessionDriver::class => [
            'key' => null,
        ],
        \Elegantly\Referer\Drivers\CookieDriver::class => [
            'key' => null,
        ],
    ],
];
