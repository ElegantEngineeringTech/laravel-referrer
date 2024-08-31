<?php

namespace Elegantly\Referrer;

use Elegantly\Referrer\Drivers\ReferrerDriver;

class Referrer
{
    /**
     * @return array<class-string<ReferrerDriver>, null|ReferrerSources>
     */
    public function getSroucesByDriver(): array
    {
        $results = [];

        foreach ($this->getDriversFromConfig() as $driver => $options) {
            $results[$driver] = $driver::get();
        }

        return $results;
    }

    /**
     * @param  class-string<ReferrerDriver>  $driver
     */
    public function getSources(?string $driver = null): ?ReferrerSources
    {
        if ($driver) {
            return $driver::get();
        }

        return array_reduce(
            $this->getSroucesByDriver(),
            function (ReferrerSources $result, ?ReferrerSources $sources) {
                if ($sources && $sources->count()) {
                    return $sources->merge($result);
                }

                return $result;
            },
            new ReferrerSources
        );
    }

    /**
     * @return array<class-string<ReferrerDriver>, mixed>
     */
    public function getDriversFromConfig(): array
    {
        /**
         * @var array<class-string<ReferrerDriver>, mixed>
         */
        return config('referrer.drivers') ?? [];
    }
}
