<?php

declare(strict_types=1);

use Elegantly\Referrer\Enums\Strategy;
use Elegantly\Referrer\Facades\Referrer;

it('retreives drivers from config', function () {

    config()->set('referrer.drivers', [
        \Elegantly\Referrer\Drivers\ContextDriver::class => [
            'key' => 'referrer',
        ],
        \Elegantly\Referrer\Drivers\SessionDriver::class,
    ]);

    $drivers = Referrer::getDriversFromConfig();

    expect($drivers)->toBe([
        \Elegantly\Referrer\Drivers\ContextDriver::class,
        \Elegantly\Referrer\Drivers\SessionDriver::class,
    ]);
});

it('retreives sources from config', function () {

    config()->set('referrer.sources', [
        \Elegantly\Referrer\Sources\UtmReferrerSource::class => [
            'strategy' => Strategy::All,
        ],
        \Elegantly\Referrer\Sources\RequestHeaderSource::class,
    ]);

    $drivers = Referrer::getSourcesFromConfig();

    expect($drivers)->toBe([
        \Elegantly\Referrer\Sources\UtmReferrerSource::class,
        \Elegantly\Referrer\Sources\RequestHeaderSource::class,
    ]);
});

it('retreives the source strategy from config', function () {

    config()->set('referrer.strategy', Strategy::Last);

    config()->set('referrer.sources', [
        \Elegantly\Referrer\Sources\UtmReferrerSource::class => [
            'strategy' => Strategy::All,
        ],
        \Elegantly\Referrer\Sources\RequestHeaderSource::class,
    ]);

    expect(Referrer::getStrategy())->toBe(Strategy::Last);

    expect(Referrer::getStrategy(
        \Elegantly\Referrer\Sources\RequestHeaderSource::class
    ))->toBe(Strategy::Last);

    expect(Referrer::getStrategy(
        \Elegantly\Referrer\Sources\UtmReferrerSource::class
    ))->toBe(Strategy::All);

});
