<?php

namespace Elegantly\Referrer\Drivers;

use Elegantly\Referrer\ReferrerSources;
use Elegantly\Referrer\Sources\ReferrerSource;

/**
 * @phpstan-type ReferrerSourceFullArray array<class-string<ReferrerSource<mixed>>, array<string, mixed>>
 * @phpstan-type ReferrerSourceArray array<class-string<ReferrerSource<mixed>>, ReferrerSource<mixed>>
 */
abstract class ReferrerDriver
{
    public static function getKey(): ?string
    {
        /**
         * @var ?string $key
         */
        $key = config('referrer.drivers.'.static::class.'.key');

        return $key;
    }

    abstract public static function put(ReferrerSources $sources): void;

    abstract public static function get(): ?ReferrerSources;

    abstract public static function forget(): void;

    public static function merge(ReferrerSources $sources): void
    {
        if ($current = static::get()) {
            static::put($current->merge($sources));
        } else {
            static::put($sources);
        }
    }

    /**
     * @param  ReferrerSourceFullArray  $sources
     */
    protected static function fromArray(array $sources): ReferrerSources
    {
        $items = new ReferrerSources;

        foreach ($sources as $source => $values) {
            $items->put($source::fromArray($values));
        }

        return $items;
    }

    /**
     * @return ReferrerSourceFullArray
     */
    protected static function toArray(ReferrerSources $sources): array
    {
        $results = [];

        foreach ($sources as $source) {
            $results[$source::class] = $source->toArray();
        }

        return $results;
    }
}
