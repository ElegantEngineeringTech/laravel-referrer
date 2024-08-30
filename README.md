# Remember the visitor referrer across requests

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elegantly/laravel-referrer.svg?style=flat-square)](https://packagist.org/packages/elegantly/laravel-referrer)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/elegantengineeringtech/laravel-referrer/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/elegantengineeringtech/laravel-referrer/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/elegantengineeringtech/laravel-referrer/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/elegantengineeringtech/laravel-referrer/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/elegantly/laravel-referrer.svg?style=flat-square)](https://packagist.org/packages/elegantly/laravel-referrer)

This small package will allow you to detect and store the visitor referrer so you can access it later.

Typical use case would be saving the referrer in database when the visitor register in your app.

The package have been designed to be flexible about:

-   How you want to detect the referrer
-   How you want to store the referrer value

## Installation

You can install the package via composer:

```bash
composer require elegantly/laravel-referrer
```

## Usage

### Capturing the visitor referrer

First, you need to publish the config file with:

```bash
php artisan vendor:publish --tag="referrer-config"
```

This is the contents of the published config file:

```php
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
        ],
    ],
];
```

In the file, enable one or more drivers by setting a value to the `key`.

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

### Retriveing the visitor referrer

You can retreive the referrer sources using the facade:

```php
\Elegantly\Referrer\Facades\Referrer::getSources(); // will merge all driver together, the first one having the priority over the next one
\Elegantly\Referrer\Facades\Referrer::getSourcesByDriver(); // will retreive all driver values
```

Here is a full example inside a controller

```php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Elegantly\Referrer\Facades\Referrer;
use Elegantly\Referrer\Sources\UtmReferrerSource;

class RegisteredUserController extends Controller
{
    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = new User($validated);

        $user->referrer = Referrer::getSource(UtmReferrerSource::class)->utm_source;

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

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Quentin Gabriele](https://github.com/QuentinGab)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
