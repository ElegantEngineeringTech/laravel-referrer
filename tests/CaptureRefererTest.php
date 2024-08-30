<?php

use Elegantly\Referer\Drivers\ContextDriver;
use Elegantly\Referer\Drivers\CookieDriver;
use Elegantly\Referer\Drivers\SessionDriver;
use Elegantly\Referer\Facades\Referer;

it('can capture referer from utm', function ($driver) {

    config()->set('referer.drivers.' . $driver . '.key', 'referer');

    $response = $this
        ->get('/?utm_source=google&utm_medium=email&utm_campaign=spring_sale&utm_id=1234&utm_term=sales&utm_content=button');

    $response->assertStatus(200);

    $referer = Referer::getSourcesByDriver();

    expect($referer[$driver])->not->toBeNull();
})->with([
    [SessionDriver::class],
    [ContextDriver::class],
    // [CookieDriver::class], Cookies can't be tested
]);

it('can capture referer from Request Header', function ($driver) {

    config()->set('referer.drivers.' . $driver . '.key', 'referer');

    $response = $this
        ->get('/', ['Referer' => 'example.com']);

    $response->assertStatus(200);

    $referer = Referer::getSourcesByDriver();

    expect($referer[$driver])->not->toBeNull();
})->with([
    [SessionDriver::class],
    [ContextDriver::class],
    // [CookieDriver::class], Cookies can't be tested
]);
