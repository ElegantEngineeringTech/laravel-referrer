<?php

declare(strict_types=1);

namespace Elegantly\Referrer;

use Elegantly\Referrer\Drivers\ReferrerDriver;
use Elegantly\Referrer\Enums\Strategy;
use Elegantly\Referrer\Sources\ReferrerSource;

class Referrer
{
    /**
     * @return array<int, class-string<ReferrerDriver>>
     */
    public function getDriversFromConfig(): array
    {
        /**
         * @var array<int|class-string<ReferrerDriver>, class-string<ReferrerDriver>|mixed[]>
         */
        $config = config('referrer.drivers') ?? [];

        $drivers = [];

        foreach ($config as $key => $value) {
            if (is_string($value)) {
                $drivers[] = $value;
            } elseif (is_string($key)) {
                $drivers[] = $key;
            }
        }

        return $drivers;
    }

    /**
     * @return array<int, class-string<ReferrerSource<mixed>>>
     */
    public function getSourcesFromConfig(): array
    {
        /**
         * @var array<int|class-string<ReferrerSource<mixed>>, class-string<ReferrerSource<mixed>>|mixed[]>
         */
        $config = config('referrer.sources') ?? [];

        $sources = [];

        foreach ($config as $key => $value) {
            if (is_string($value)) {
                $sources[] = $value;
            } elseif (is_string($key)) {
                $sources[] = $key;
            }
        }

        return $sources;
    }

    /**
     * @param  null|class-string<ReferrerSource<mixed>>|ReferrerSource<mixed>  $source
     */
    public function getStrategy(null|ReferrerSource|string $source = null): Strategy
    {
        if ($source) {
            $source = $source instanceof ReferrerSource ? $source::class : $source;

            /**
             * @var ?Strategy
             */
            $config = config("referrer.sources.{$source}.strategy");

            return $config ?: $this->getStrategy();
        }

        /**
         * @var Strategy
         */
        $strategy = config('referrer.strategy', Strategy::All);

        return $strategy;
    }

    /**
     * @return array<class-string<ReferrerDriver>, null|ReferrerSources>
     */
    public function getSourcesByDriver(): array
    {
        $results = [];

        $drivers = $this->getDriversFromConfig();

        foreach ($drivers as $driver) {
            $results[$driver] = $driver::make()->get();
        }

        return $results;
    }

    /**
     * @param  null|class-string<ReferrerDriver>  $driver
     */
    public function getSources(
        ?string $driver = null
    ): ReferrerSources {
        if ($driver) {
            return $driver::make()->get() ?? new ReferrerSources;
        }

        $sources = new ReferrerSources;

        $drivers = $this->getDriversFromConfig();

        foreach ($drivers as $driver) {
            $sources = $sources->merge($driver::make()->get());
        }

        return $sources;
    }

    /**
     * @param  null|class-string<ReferrerDriver>  $driver
     */
    public function forget(
        ?string $driver = null
    ): void {
        if ($driver) {
            $driver::make()->forget();
        } else {
            $drivers = $this->getDriversFromConfig();

            foreach ($drivers as $driver) {
                $driver::make()->forget();
            }
        }

    }

    /**
     * @param  null|class-string<ReferrerDriver>  $driver
     */
    public function put(
        ReferrerSources $sources,
        ?string $driver = null
    ): void {
        if ($driver) {
            $driver::make()->put($sources);
        } else {
            $drivers = $this->getDriversFromConfig();

            foreach ($drivers as $driver) {
                $driver::make()->put($sources);
            }
        }
    }
}
