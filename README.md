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

You can publish the config file with:

```bash
php artisan vendor:publish --tag="referrer-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$referrer = new Elegantly\Referrer();
echo $referrer->echoPhrase('Hello, Elegantly!');
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
