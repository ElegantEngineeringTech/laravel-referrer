<?php

namespace Elegantly\Referer\Drivers;

use Elegantly\Referer\Sources\RefererSource;

/**
 * @phpstan-type RefererSourceFullArray array<class-string<RefererSource<mixed>>, array<string, mixed>>
 * @phpstan-type RefererSourceArray array<class-string<RefererSource<mixed>>, RefererSource<mixed>>
 */
abstract class RefererDriver
{
    public static function getKey(): ?string
    {
        /**
         * @var ?string $key
         */
        $key = config('referer.drivers.'.static::class.'.key');

        return $key;
    }

    /**
     * @param  RefererSource<mixed>[]  $sources
     */
    abstract public static function put(array $sources): void;

    /**
     * @return null|RefererSourceArray
     */
    abstract public static function get(): ?array;

    abstract public static function forget(): void;

    /**
     * @param  RefererSourceFullArray  $sources
     * @return RefererSourceArray
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
     * @param  RefererSource<mixed>[]  $sources
     * @return RefererSourceFullArray
     */
    protected static function toArray(array $sources): array
    {
        $results = [];

        foreach ($sources as $source) {
            /**
             * @var class-string<RefererSource<mixed>> $className
             */
            $className = get_class($source);
            $results[$className] = $source->toArray();
        }

        return $results;
    }
}
