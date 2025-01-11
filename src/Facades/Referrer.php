<?php

declare(strict_types=1);

namespace Elegantly\Referrer\Facades;

use Elegantly\Referrer\Drivers\ReferrerDriver;
use Elegantly\Referrer\Enums\Strategy;
use Elegantly\Referrer\ReferrerSources;
use Elegantly\Referrer\Sources\ReferrerSource;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array<class-string<ReferrerDriver>, mixed> getDriversFromConfig()
 * @method static array<int, class-string<ReferrerSource<mixed>>> getSourcesFromConfig()
 * @method static Strategy getStrategy(null|class-string<ReferrerSource<mixed>>|ReferrerSource<mixed> $source = null)
 * @method static array<class-string<ReferrerDriver>, ReferrerSources> getSourcesByDriver()
 * @method static ReferrerSources getSources(null|class-string<ReferrerDriver>  $driver = null)
 * @method static void put(ReferrerSources $sources, null|class-string<ReferrerDriver>  $driver = null)
 * @method static void forget(null|class-string<ReferrerDriver>  $driver = null)
 *
 * @see \Elegantly\Referrer\Referrer
 */
class Referrer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Elegantly\Referrer\Referrer::class;
    }
}
