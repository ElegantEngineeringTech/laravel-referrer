<?php

namespace Elegantly\Referrer\Drivers;

use Illuminate\Support\Facades\Session;

/**
 * @phpstan-import-type ReferrerSourceFullArray from ReferrerDriver
 */
class SessionDriver extends ReferrerDriver
{
    public static function put(array $sources): void
    {
        if ($key = static::getKey()) {
            Session::put(
                $key,
                static::toArray($sources)
            );
        }
    }

    public static function get(): ?array
    {
        if ($key = static::getKey()) {
            /**
             * @var ReferrerSourceFullArray $sources
             */
            $sources = Session::get($key);

            if ($sources) {
                return static::fromArray($sources);
            }
        }

        return null;
    }

    public static function forget(): void
    {
        if ($key = static::getKey()) {
            Session::forget($key);
        }
    }
}
