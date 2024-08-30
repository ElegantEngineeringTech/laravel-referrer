# Remember the visitor referer across requests

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elegantly/laravel-referer.svg?style=flat-square)](https://packagist.org/packages/elegantly/laravel-referer)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/elegantengineeringtech/laravel-referer/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/elegantengineeringtech/laravel-referer/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/elegantengineeringtech/laravel-referer/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/elegantengineeringtech/laravel-referer/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/elegantly/laravel-referer.svg?style=flat-square)](https://packagist.org/packages/elegantly/laravel-referer)

This small package will allow you to detect and store the visitor referer so you can access it later.

Typical use case would be saving the referer in database when the visitor register in your app.

The package have been designed to be flexible about:

-   How you want to detect the referer
-   How you want to store the referer value

## Installation

You can install the package via composer:

```bash
composer require elegantly/laravel-referer
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="referer-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$referer = new Elegantly\Referer();
echo $referer->echoPhrase('Hello, Elegantly!');
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
