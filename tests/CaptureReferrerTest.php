<?php

use Elegantly\Referrer\Drivers\ContextDriver;
use Elegantly\Referrer\Drivers\CookieDriver;
use Elegantly\Referrer\Drivers\SessionDriver;
use Elegantly\Referrer\Facades\Referrer;
use Elegantly\Referrer\Sources\RequestHeaderSource;
use Elegantly\Referrer\Sources\UtmReferrerSource;

it('can capture referrer from utm', function ($driver) {

    config()->set('referrer.drivers.'.$driver.'.key', 'referrer');

    $this
        ->get('/?utm_source=google&utm_medium=email&utm_campaign=spring_sale&utm_id=1234&utm_term=sales&utm_content=button', ['Referer' => 'example.com'])
        ->assertStatus(200);

    $this->get('/'); // this sould not override the captured referrer

    $source = Referrer::getSources($driver)->get(UtmReferrerSource::class);

    expect($source?->utm_source)->toBe('google');
    expect($source?->utm_campaign)->toBe('spring_sale');
    expect($source?->utm_id)->toBe('1234');
    expect($source?->utm_term)->toBe('sales');
    expect($source?->utm_content)->toBe('button');

    $source = Referrer::getSources($driver)->get(RequestHeaderSource::class);

    expect($source?->referer)->toBe('example.com');
})->with([
    [SessionDriver::class],
    [ContextDriver::class],
    // [CookieDriver::class], Cookies can't be tested
]);
