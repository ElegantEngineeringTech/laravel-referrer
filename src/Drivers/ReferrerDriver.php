<?php

namespace Elegantly\Referrer\Drivers;

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

    /**
     * @param  ReferrerSource<mixed>[]  $sources
     */
    abstract public static function put(array $sources): void;

    /**
     * @return null|ReferrerSourceArray
     */
    abstract public static function get(): ?array;

    abstract public static function forget(): void;

    /**
     * @param  ReferrerSource<mixed>[]  $sources
     */
    public static function merge(array $sources): void
    {
        static::put(array_merge(
            static::get() ?? [],
            $sources,
        ));
    }

    /**
     * @param  ReferrerSourceFullArray  $sources
     * @return ReferrerSourceArray
     */
    protected static function fromArray(array $sources): array
    {
        $results = [];

        foreach ($sources as $source => $values) {
            $results[$source] = $source::fromArray($values);
        }

        return $results;
    }

    /**
     * @param  ReferrerSource<mixed>[]  $sources
     * @return ReferrerSourceFullArray
     */
    protected static function toArray(array $sources): array
    {
        $results = [];

        foreach ($sources as $source) {
            /**
             * @var class-string<ReferrerSource<mixed>> $className
             */
            $className = get_class($source);
            $results[$className] = $source->toArray();
        }

        return $results;
    }
}
