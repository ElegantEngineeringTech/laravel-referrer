<?php

use Elegantly\Referrer\ReferrerSources;
use Elegantly\Referrer\Sources\RequestHeaderSource;
use Elegantly\Referrer\Sources\UtmReferrerSource;

it('puts a source in the items', function () {
    $sources = new ReferrerSources;

    $sources
        ->put(new RequestHeaderSource(
            referer: 'foo'
        ))
        ->put(new UtmReferrerSource(
            utm_source: 'fake'
        ))
        ->put(new UtmReferrerSource(
            utm_source: 'google'
        ));

    expect($sources->get(RequestHeaderSource::class)->referer)->toBe('foo');
    expect($sources->get(UtmReferrerSource::class)->utm_source)->toBe('google');
});

it('forget a source from the items', function () {
    $sources = new ReferrerSources;

    $sources
        ->put(new RequestHeaderSource(
            referer: 'foo'
        ))
        ->put(new UtmReferrerSource(
            utm_source: 'google'
        ));

    expect($sources->items)->toHaveLength(2);

    $sources->forget(UtmReferrerSource::class);

    expect($sources->items)->toHaveLength(1);
});
