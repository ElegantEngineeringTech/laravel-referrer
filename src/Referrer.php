<?php

namespace Elegantly\Referrer;

use Elegantly\Referrer\Drivers\ReferrerDriver;

/**
 * @phpstan-import-type ReferrerSourceArray from ReferrerDriver
 *
 * @phpstan-type ReferrerDriverArray array<class-string<ReferrerDriver>, ReferrerSourceArray|null>
 */
class Referrer
{
    /**
     * @return ReferrerDriverArray
     */
    public function getSourcesByDriver(): array
    {
        /**
         * @var array<class-string<ReferrerDriver>, mixed> $drivers
         */
        $drivers = config('referrer.drivers');

        $results = [];

        foreach ($drivers as $driver => $options) {
            $results[$driver] = $driver::get();
        }

        return $results;
    }

    /**
     * @return ReferrerSourceArray
     */
    public function getSources(): array
    {
        return array_reduce(
            $this->getSourcesByDriver(),
            function (array $result, ?array $sources) {
                if ($sources) {
                    return array_merge($sources, $result);
                }

                return $result;
            },
            []
        );
    }
}
