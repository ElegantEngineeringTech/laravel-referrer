# Remember the Visitor Referrer Across Requests

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elegantly/laravel-referrer.svg?style=flat-square)](https://packagist.org/packages/elegantly/laravel-referrer)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/elegantengineeringtech/laravel-referrer/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/elegantengineeringtech/laravel-referrer/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/elegantengineeringtech/laravel-referrer/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/elegantengineeringtech/laravel-referrer/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/elegantly/laravel-referrer.svg?style=flat-square)](https://packagist.org/packages/elegantly/laravel-referrer)

This small package allows you to detect and store the visitor referrer so that you can access it later.

A typical use case would be saving the referrer in a database when a visitor registers in your app.

The package is designed to be flexible regarding:

-   How you want to detect the referrer
-   How you want to store the referrer value

## Installation

You can install the package via Composer:

```bash
composer require elegantly/laravel-referrer
```

## Usage

### Capturing the Visitor Referrer

First, publish the config file with:

```bash
php artisan vendor:publish --tag="referrer-config"
```

This is the content of the published config file:

```php
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
```

In the file, enable one or more drivers by uncommenting them.

Next, add the `CaptureReferrerMiddleware` to your route:

[Using Laravel v11](https://laravel.com/docs/11.x/middleware#registering-middleware):

```php
use Elegantly\Referrer\CaptureReferrerMiddleware;

->withMiddleware(function (Middleware $middleware) {
     $middleware->append(CaptureReferrerMiddleware::class);
})
```

[Using Laravel v10](https://laravel.com/docs/10.x/middleware#global-middleware):

```php
use Elegantly\Referrer\CaptureReferrerMiddleware;

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'web' => [
            // ...
            \Elegantly\Referrer\CaptureReferrerMiddleware::class,
        ],
    ];
}
```

### Handling Multiple Referrers

What if a user encounters multiple referrers during their navigation? For example, a user might have clicked on multiple Google Ads campaigns or links with different UTM parameters.

In the config file, you can define the strategy to adopt for these cases. Here are some examples:

#### Capture the Latest Referrer

```php
use Elegantly\Referrer\Enums\Strategy;
return [
    //...
    'strategy' => Strategy::Last,
    //...
]
```

This will store only the most recent referrer value and discard any previous ones.

#### Capture the First Referrer

```php
use Elegantly\Referrer\Enums\Strategy;
return [
    //...
    'strategy' => Strategy::First,
    //...
]
```

This will store the first referrer value encountered and ignore any subsequent ones.

#### Capture All Referrers

```php
use Elegantly\Referrer\Enums\Strategy;
return [
    //...
    'strategy' => Strategy::All,
    //...
]
```

This will store all referrer values encountered.

### Configuring the Strategy per Source

You can also define a different strategy for each referrer source:

```php
use Elegantly\Referrer\Enums\Strategy;
return [
    //...
    'strategy' => Strategy::Last, // Global strategy

    'sources' => [
        \Elegantly\Referrer\Sources\UtmReferrerSource::class => [
            'strategy' => Strategy::First, // Custom strategy for UTM referrers
        ],
        \Elegantly\Referrer\Sources\RequestHeaderSource::class,
        \Elegantly\Referrer\Sources\GoogleClickIdSource::class,
        \Elegantly\Referrer\Sources\MetaClickIdSource::class,
        \Elegantly\Referrer\Sources\TikTokClickIdSource::class,
    ],
    //...
]
```

In this example, the global strategy is set to `Strategy::Last`, but for the `UtmReferrerSource`, the strategy is set to `Strategy::First`.

### Retrieving the Visitor Referrer

You can retrieve the referrer sources using the facade:

```php
\Elegantly\Referrer\Facades\Referrer::getSources(); // Merges all drivers together, with the last one having priority over the previous ones
\Elegantly\Referrer\Facades\Referrer::getSourcesByDriver(); // Retrieves all driver values
```

Here is a full example inside a controller:

```php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Elegantly\Referrer\Facades\Referrer;
use Elegantly\Referrer\Sources\UtmReferrerSource;

class RegisteredUserController extends Controller
{
    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = new User($validated);

        $user->referrer = Referrer::getSources()->getFirst(UtmReferrerSource::class)?->utm_source;

        $user->save();

        Auth::login($user);

        return redirect("/");
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) for information on how to report security vulnerabilities.

## Credits

-   [Quentin Gabriele](https://github.com/QuentinGab)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.
