<?php

namespace Elegantly\Referrer;

use Elegantly\Referrer\Drivers\ReferrerDriver;
use Elegantly\Referrer\Sources\ReferrerSource;
use Illuminate\Support\Collection;

/**
 * @phpstan-import-type ReferrerSourceArray from ReferrerDriver
 *
 * @phpstan-type ReferrerDriverArray array<class-string<ReferrerDriver>, ReferrerSourceArray|null>
 */
class Referrer
{
    /**
     * @param  class-string<ReferrerDriver>  $driver
     * @return ReferrerSourceArray|null
     */
    public function getDriver(string $driver): ?array
    {
        return $driver::get();
    }

    /**
     * @template T of ReferrerSource<mixed>
     *
     * @param  class-string<T>  $source
     * @param  null|class-string<ReferrerDriver>  $driver
     * @return null|T
     */
    public function getSource(string $source, ?string $driver = null)
    {
        if ($driver && $driverValue = $this->getDriver($driver)) {
            /** @var null|T */
            $value = $driverValue[$source] ?? null;
        } else {
            /** @var null|T */
            $value = $this->getSources()[$source] ?? null;
        }

        return $value;
    }

    /**
     * @return ReferrerDriverArray
     */
    public function getDrivers(): array
    {
        /**
         * @var array<class-string<ReferrerDriver>, mixed> $drivers
         */
        $drivers = config('referrer.drivers') ?? [];

        $results = [];

        foreach ($drivers as $driver => $options) {
            $results[$driver] = $this->getDriver($driver);
        }

        return $results;
    }

    /**
     * @return ReferrerSourceArray
     */
    public function getSources(): array
    {
        return array_reduce(
            $this->getDrivers(),
            function (array $result, ?array $sources) {
                if ($sources) {
                    return array_merge($sources, $result);
                }

                return $result;
            },
            []
        );
    }

    /**
     * @return Collection<class-string<ReferrerDriver>, null|ReferrerSourceArray>
     */
    public function collectDrivers(): Collection
    {
        return collect($this->getDrivers());
    }

    /**
     * @return Collection<class-string<ReferrerSource<mixed>>, ReferrerSource<mixed>>
     */
    public function collectSources(): Collection
    {
        return collect($this->getSources());
    }
}
