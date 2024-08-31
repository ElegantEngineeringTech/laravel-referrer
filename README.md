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
return [
    /*
    |--------------------------------------------------------------------------
    | Referrer Sources
    |--------------------------------------------------------------------------
    |
    | These are the classes containing the logic to detect the visitor's referrer.
    | You can disable some of them or add as many as you want.
    | Regardless of the number of sources you define, they will all be stored.
    |
    */
    'sources' => [
        \Elegantly\Referrer\Sources\UtmReferrerSource::class,
        \Elegantly\Referrer\Sources\RequestHeaderSource::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Referrer Drivers
    |--------------------------------------------------------------------------
    |
    | These are the classes containing the logic to store the visitor's referrer.
    | By default, they are all disabled. To enable a driver, add a "key" value.
    | You can also add your own driver if needed.
    | Regardless of the number of drivers you define, they will all store the referrer sources.
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
        ],
    ],
];
```

In the file, enable one or more drivers by setting a value for the `key`.

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

### Retrieving the Visitor Referrer

You can retrieve the referrer sources using the facade:

```php
\Elegantly\Referrer\Facades\Referrer::getSources(); // Merges all drivers together, with the first one having priority over the next ones
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

        $user->referrer = Referrer::getSources()->get(UtmReferrerSource::class)->utm_source;

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
