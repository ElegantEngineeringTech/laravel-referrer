<?php

namespace Elegantly\Referer;

use Elegantly\Referer\Drivers\RefererDriver;

/**
 * @phpstan-import-type RefererSourceArray from RefererDriver
 *
 * @phpstan-type RefererDriverArray array<class-string<RefererDriver>, RefererSourceArray|null>
 */
class Referer
{
    /**
     * @return RefererDriverArray
     */
    public function getSourcesByDriver(): array
    {
        /**
         * @var array<class-string<RefererDriver>, mixed> $drivers
         */
        $drivers = config('referer.drivers');

        $results = [];

        foreach ($drivers as $driver => $options) {
            $results[$driver] = $driver::get();
        }

        return $results;
    }

    /**
     * @return RefererSourceArray
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
