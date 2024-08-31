<?php

namespace Elegantly\Referrer;

use Elegantly\Referrer\Drivers\ReferrerDriver;

class Referrer
{
    /**
     * @return array<class-string<ReferrerDriver>, null|ReferrerSources>
     */
    public function getSourcesByDriver(): array
    {
        $results = [];

        foreach ($this->getDriversFromConfig() as $driverName => $options) {
            $results[$driverName] = $driverName::make()?->get();
        }

        return $results;
    }

    /**
     * @param  class-string<ReferrerDriver>  $driverName
     */
    public function get(?string $driverName = null): ReferrerSources
    {
        if ($driverName) {
            return $driverName::make()?->get() ?? new ReferrerSources;
        }

        return array_reduce(
            $this->getSourcesByDriver(),
            function (ReferrerSources $result, ?ReferrerSources $sources) {
                if ($sources && $sources->count()) {
                    return $sources->merge($result);
                }

                return $result;
            },
            new ReferrerSources
        );
    }

    public function forget(): void
    {
        foreach ($this->getDriversFromConfig() as $driverName => $options) {
            $driverName::make()?->forget();
        }
    }

    public function put(ReferrerSources $sources): void
    {
        foreach ($this->getDriversFromConfig() as $driverName => $options) {
            $driverName::make()?->put($sources);
        }
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
