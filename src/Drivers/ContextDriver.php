<?php

namespace Elegantly\Referer\Drivers;

use Illuminate\Support\Facades\Context;

/**
 * @phpstan-import-type RefererSourceFullArray from RefererDriver
 */
class ContextDriver extends RefererDriver
{
    public static function put(array $sources): void
    {
        if ($key = static::getKey()) {
            Context::add(
                $key,
                static::toArray($sources)
            );
        }
    }

    public static function get(): ?array
    {
        if ($key = static::getKey()) {
            /**
             * @var RefererSourceFullArray $sources
             */
            $sources = Context::get($key);

            if ($sources) {
                return static::fromArray($sources);
            }
        }

        return null;
    }

    public static function forget(): void
    {
        if ($key = static::getKey()) {
            Context::forget($key);
        }
    }
}
