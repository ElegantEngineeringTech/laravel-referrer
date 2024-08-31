<?php

namespace Elegantly\Referrer\Drivers;

use Elegantly\Referrer\ReferrerSources;
use Illuminate\Support\Facades\Context;

/**
 * @phpstan-import-type ReferrerSourceFullArray from ReferrerDriver
 */
class ContextDriver extends ReferrerDriver
{
    public static function put(ReferrerSources $sources): void
    {
        if ($key = static::getKey()) {
            Context::add(
                $key,
                static::toArray($sources)
            );
        }
    }

    public static function get(): ?ReferrerSources
    {
        if ($key = static::getKey()) {
            /**
             * @var null|ReferrerSourceFullArray $sources
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
