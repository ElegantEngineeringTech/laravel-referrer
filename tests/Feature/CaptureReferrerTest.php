<?php

use Elegantly\Referrer\Drivers\ContextDriver;
use Elegantly\Referrer\Drivers\SessionDriver;
use Elegantly\Referrer\Enums\Strategy;
use Elegantly\Referrer\Facades\Referrer;
use Elegantly\Referrer\Sources\ReferrerSource;
use Elegantly\Referrer\Sources\RequestHeaderSource;
use Elegantly\Referrer\Sources\UtmReferrerSource;

it('can capture the referrer', function ($driver) {

    config()->set('referrer.drivers', [
        $driver => [
            'key' => 'referrer',
        ],
    ]);

    $this
        ->get('/?utm_source=google&utm_medium=email&utm_campaign=spring_sale&utm_id=1234&utm_term=sales&utm_content=button', ['Referer' => 'example.com'])
        ->assertStatus(200);

    $this->get('/'); // this sould not override the captured referrer

    $source = Referrer::getSources($driver)->getFirst(UtmReferrerSource::class);

    expect($source?->utm_source)->toBe('google');
    expect($source?->utm_campaign)->toBe('spring_sale');
    expect($source?->utm_id)->toBe('1234');
    expect($source?->utm_term)->toBe('sales');
    expect($source?->utm_content)->toBe('button');

    $source = Referrer::getSources($driver)->getFirst(RequestHeaderSource::class);

    expect($source?->referer)->toBe('example.com');
})->with([
    [SessionDriver::class],
    [ContextDriver::class],
]);

it('can capture the referrer using all strategy', function ($driver) {

    config()->set('referrer.strategy', Strategy::All);

    config()->set('referrer.drivers', [
        $driver => [
            'key' => 'referrer',
        ],
    ]);

    $this
        ->get('/?utm_source=first')
        ->assertStatus(200);

    $this
        ->get('/?utm_source=first')
        ->assertStatus(200); // this should not be stored

    $this
        ->get('/?utm_source=second')
        ->assertStatus(200);

    expect(
        Referrer::getSources($driver)->get(UtmReferrerSource::class)
    )->toHaveLength(2);

    expect(
        Referrer::getSources($driver)
            ->getFirst(UtmReferrerSource::class)
            ?->utm_source
    )->toBe('first');

    expect(
        Referrer::getSources($driver)
            ->getLast(UtmReferrerSource::class)
            ?->utm_source
    )->toBe('second');

})->with([
    [SessionDriver::class],
    [ContextDriver::class],
]);

it('can capture the referrer using first strategy', function ($driver) {

    config()->set('referrer.strategy', Strategy::First);

    config()->set('referrer.drivers', [
        $driver => [
            'key' => 'referrer',
        ],
    ]);

    $this
        ->get('/?utm_source=first')
        ->assertStatus(200);

    $this
        ->get('/?utm_source=second')
        ->assertStatus(200);

    expect(
        Referrer::getSources($driver)->get(UtmReferrerSource::class)
    )->toHaveLength(1);

    expect(
        Referrer::getSources($driver)
            ->getFirst(UtmReferrerSource::class)
            ?->utm_source
    )->toBe('first');

    expect(
        Referrer::getSources($driver)
            ->getLast(UtmReferrerSource::class)
            ?->utm_source
    )->toBe('first');

})->with([
    [SessionDriver::class],
    [ContextDriver::class],
]);

it('can capture the referrer using last strategy', function ($driver) {

    config()->set('referrer.strategy', Strategy::Last);

    config()->set('referrer.drivers', [
        $driver => [
            'key' => 'referrer',
        ],
    ]);

    $this
        ->get('/?utm_source=first')
        ->assertStatus(200);

    $this
        ->get('/?utm_source=second')
        ->assertStatus(200);

    expect(
        Referrer::getSources($driver)->get(UtmReferrerSource::class)
    )->toHaveLength(1);

    expect(
        Referrer::getSources($driver)
            ->getFirst(UtmReferrerSource::class)
            ?->utm_source
    )->toBe('second');

    expect(
        Referrer::getSources($driver)
            ->getLast(UtmReferrerSource::class)
            ?->utm_source
    )->toBe('second');

})->with([
    [SessionDriver::class],
    [ContextDriver::class],
]);

it('retreives the oldest and latest captured source', function ($driver) {

    config()->set('referrer.strategy', Strategy::All);

    config()->set('referrer.drivers', [
        $driver => [
            'key' => 'referrer',
        ],
    ]);

    $this
        ->get('/?utm_source=first')
        ->assertStatus(200);

    $this
        ->get('/?utm_source=second')
        ->assertStatus(200);

    $this
        ->get('/?utm_source=third')
        ->assertStatus(200);

    $oldest = Referrer::getSources()->getOldest();
    $latest = Referrer::getSources()->getLatest();

    expect($oldest)->toBeInstanceOf(ReferrerSource::class);
    expect($latest)->toBeInstanceOf(ReferrerSource::class);

    expect($oldest?->__toString())->toBe('utm_source=first');
    expect($latest?->__toString())->toBe('utm_source=third');

})->with([
    [SessionDriver::class],
]);
