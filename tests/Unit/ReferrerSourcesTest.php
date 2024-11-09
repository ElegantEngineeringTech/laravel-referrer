<?php

use Elegantly\Referrer\Enums\Strategy;
use Elegantly\Referrer\ReferrerSources;
use Elegantly\Referrer\Sources\RequestHeaderSource;
use Elegantly\Referrer\Sources\UtmReferrerSource;

it('puts a source in the items using all strategy', function () {
    $sources = new ReferrerSources;

    $sources
        ->put(
            source: new RequestHeaderSource(
                referer: 'foo'
            ),
            strategy: Strategy::All
        )
        ->put(
            source: new UtmReferrerSource(
                utm_source: 'fake'
            ),
            strategy: Strategy::All
        )
        ->put(
            source: new UtmReferrerSource(
                utm_source: 'google'
            ),
            strategy: Strategy::All
        );

    $headers = $sources->get(RequestHeaderSource::class);
    expect($headers)->toBeArray();
    expect($headers)->toHaveLength(1);
    expect($sources->getFirst(RequestHeaderSource::class)?->referer)->toBe('foo');

    $utms = $sources->get(UtmReferrerSource::class);
    expect($utms)->toBeArray();
    expect($utms)->toHaveLength(2);
    expect($utms[0]->utm_source)->toBe('fake');
    expect($utms[1]->utm_source)->toBe('google');
    expect($sources->getFirst(UtmReferrerSource::class)?->utm_source)->toBe('fake');
    expect($sources->getLast(UtmReferrerSource::class)?->utm_source)->toBe('google');
});

it('puts a source in the items using first strategy', function () {
    $sources = new ReferrerSources;

    $sources
        ->put(
            source: new RequestHeaderSource(
                referer: 'foo'
            ),
            strategy: Strategy::First
        )
        ->put(
            source: new UtmReferrerSource(
                utm_source: 'fake'
            ),
            strategy: Strategy::First
        )
        ->put(
            source: new UtmReferrerSource(
                utm_source: 'google'
            ),
            strategy: Strategy::First
        );

    $headers = $sources->get(RequestHeaderSource::class);
    expect($headers)->toBeArray();
    expect($headers)->toHaveLength(1);
    expect($sources->getFirst(RequestHeaderSource::class)?->referer)->toBe('foo');

    $utms = $sources->get(UtmReferrerSource::class);
    expect($utms)->toBeArray();
    expect($utms)->toHaveLength(1);
    expect($utms[0]->utm_source)->toBe('fake');
    expect($sources->getFirst(UtmReferrerSource::class)?->utm_source)->toBe('fake');
});

it('puts a source in the items using last strategy', function () {
    $sources = new ReferrerSources;

    $sources
        ->put(
            source: new RequestHeaderSource(
                referer: 'foo'
            ),
            strategy: Strategy::Last
        )
        ->put(
            source: new UtmReferrerSource(
                utm_source: 'fake'
            ),
            strategy: Strategy::Last
        )
        ->put(
            source: new UtmReferrerSource(
                utm_source: 'google'
            ),
            strategy: Strategy::Last
        );

    $headers = $sources->get(RequestHeaderSource::class);
    expect($headers)->toBeArray();
    expect($headers)->toHaveLength(1);
    expect($sources->getFirst(RequestHeaderSource::class)?->referer)->toBe('foo');

    $utms = $sources->get(UtmReferrerSource::class);
    expect($utms)->toBeArray();
    expect($utms)->toHaveLength(1);
    expect($utms[0]->utm_source)->toBe('google');
    expect($sources->getFirst(UtmReferrerSource::class)?->utm_source)->toBe('google');
});

it('forget a source from the items', function () {
    $sources = new ReferrerSources;

    $sources
        ->put(
            source: new RequestHeaderSource(
                referer: 'foo'
            ),
            strategy: Strategy::All
        )
        ->put(
            source: new UtmReferrerSource(
                utm_source: 'google'
            ),
            strategy: Strategy::All
        )
        ->put(
            source: new UtmReferrerSource(
                utm_source: 'fake'
            ),
            strategy: Strategy::All
        );

    expect($sources->count())->toBe(2);

    $sources->forget(UtmReferrerSource::class);

    expect($sources->count())->toBe(1);
});