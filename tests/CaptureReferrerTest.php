<?php

use Elegantly\Referrer\Drivers\ContextDriver;
use Elegantly\Referrer\Drivers\CookieDriver;
use Elegantly\Referrer\Drivers\SessionDriver;
use Elegantly\Referrer\Facades\Referrer;

it('can capture referrer from utm', function ($driver) {

    config()->set('referrer.drivers.'.$driver.'.key', 'referrer');

    $response = $this
        ->get('/?utm_source=google&utm_medium=email&utm_campaign=spring_sale&utm_id=1234&utm_term=sales&utm_content=button');

    $response->assertStatus(200);

    $referrer = Referrer::getDrivers();

    expect($referrer[$driver])->not->toBeNull();
})->with([
    [SessionDriver::class],
    [ContextDriver::class],
    // [CookieDriver::class], Cookies can't be tested
]);

it('can capture referrer from Request Header', function ($driver) {

    config()->set('referrer.drivers.'.$driver.'.key', 'referrer');

    $response = $this
        ->get('/', ['Referrer' => 'example.com']);

    $response->assertStatus(200);

    $referrer = Referrer::getDrivers();

    expect($referrer[$driver])->not->toBeNull();
})->with([
    [SessionDriver::class],
    [ContextDriver::class],
    // [CookieDriver::class], Cookies can't be tested
]);
