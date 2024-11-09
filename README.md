# Remember the Visitor Referrer Across Requests

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elegantly/laravel-referrer.svg?style=flat-square)](https://packagist.org/packages/elegantly/laravel-referrer)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/elegantengineeringtech/laravel-referrer/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/elegantengineeringtech/laravel-referrer/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/elegantengineeringtech/laravel-referrer/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/elegantengineeringtech/laravel-referrer/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/elegantly/laravel-referrer.svg?style=flat-square)](https://packagist.org/packages/elegantly/laravel-referrer)

This package allows you to detect and store visitor referrers so that you can access them later.
A typical use case would be saving the referrer in a database when a visitor registers in your app.

Out of the box, it captures:

-   Google Click ID (gclid)
-   Meta Click ID (fbclid)
-   TikTok Click ID (ttclid)
-   Header referrer value
-   All URL UTM values
-   Or add your own source

The package is designed to be highly flexible regarding:

-   How you want to detect the referrer
-   How you want to store the referrer value

Out of the box, it can store referrer values in:

-   Laravel Context
-   Laravel Session
-   A Cookie
-   Or add your own driver

## Installation

You can install the package via Composer:

```bash
composer require elegantly/laravel-referrer
```

## Usage

First, publish the config file with:

```bash
php artisan vendor:publish --tag="referrer-config"
```

### Configuration

Then configure your sources and drivers:

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

In the configuration file, enable one or more drivers by uncommenting them.

### Middleware Configuration

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

Once configured, your referrer sources will be automatically captured.

### Choosing a Strategy

What happens when a user encounters multiple referrers during their navigation?
For example, a user might click on multiple Google Ads campaigns or links with different UTM parameters.

This package supports 3 capture strategies:

-   First: Only capture the first referrer of each source
-   Last: Only capture the last referrer of each source
-   All: Capture all referrers

From the config, you can define a strategy globally using:

```php
use Elegantly\Referrer\Enums\Strategy;

return [
    //...
    'strategy' => Strategy::Last,
    //...
]
```

You can also define the strategy per source like this:

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

### Retrieving the Visitor Referrer

You can retrieve the referrer sources using the facade:

```php
use \Elegantly\Referrer\Facades\Referrer;

Referrer::getSources(); // Merges all drivers together, with the last one having priority
Referrer::getSourcesByDriver(); // Retrieves all driver values
```

Retrieve the first or last referrer value:

```php
use \Elegantly\Referrer\Sources\UtmReferrerSource;
use \Elegantly\Referrer\Sources\GoogleClickIdSource;
use \Elegantly\Referrer\Facades\Referrer;

Referrer::getSources()->getFirst(UtmReferrerSource::class);
// Returns an instance of UtmReferrerSource

Referrer::getSources()->getLast(GoogleClickIdSource::class);
// Returns an instance of GoogleClickIdSource
```

Retrieve the oldest or latest referrer value from any source:

```php
use \Elegantly\Referrer\Facades\Referrer;

Referrer::getSources()->getOldest();
// Returns an instance of ReferrerSource

Referrer::getSources()->getLatest();
// Returns an instance of ReferrerSource
```

Here is a complete example inside a controller:

```php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Elegantly\Referrer\Facades\Referrer;
use \Elegantly\Referrer\Sources\UtmReferrerSource;

class RegisteredUserController extends Controller
{
    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = new User($validated);

        /**
         * The value returned will be the latest referrer value captured among all sources
         */
        $user->referrer = (string) Referrer::getSources()->getLatest();

        /**
         * The value returned will be the first UTM captured
         */
        $user->utm = (string) Referrer::getSources()->getFirst(UtmReferrerSource::class);

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
