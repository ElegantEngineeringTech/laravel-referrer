<?php

namespace Elegantly\Referrer\Drivers;

use Elegantly\Referrer\ReferrerSources;
use Illuminate\Support\Facades\Cookie;

/**
 * @phpstan-import-type ReferrerSourceFullArray from ReferrerDriver
 */
class CookieDriver extends ReferrerDriver
{
    public static function put(ReferrerSources $sources): void
    {
        if ($key = static::getKey()) {
            Cookie::queue(
                $key,
                static::toArray($sources)
            );
        }
    }

    public static function get(): ?ReferrerSources
    {
        if ($key = static::getKey()) {
            $cookie = Cookie::get($key);

            /**
             * @var null|ReferrerSourceFullArray $sources
             */
            $sources = is_string($cookie) ? json_decode($cookie) : null;

            if ($sources) {
                return static::fromArray($sources);
            }
        }

        return null;
    }

    public static function forget(): void
    {
        if ($key = static::getKey()) {
            Cookie::forget($key);
        }
    }
}
