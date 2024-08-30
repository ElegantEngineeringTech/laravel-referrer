<?php

namespace Elegantly\Referrer\Facades;

use Elegantly\Referrer\Drivers\ReferrerDriver;
use Elegantly\Referrer\Sources\ReferrerSource;
use Illuminate\Support\Facades\Facade;

/**
 * @phpstan-import-type ReferrerSourceArray from ReferrerDriver
 * @phpstan-import-type ReferrerDriverArray from \Elegantly\Referrer\Referrer
 *
 * @template TSource of ReferrerSource<mixed>
 *
 * @method static ReferrerSourceArray|null getDriver()
 * @method static ReferrerDriverArray getDrivers()
 * @method static null|TSource getSource(class-string<TSource> $source, class-string<ReferrerDriver> $driver)
 * @method static ReferrerSourceArray getSources()
 * @method static Collection<class-string<ReferrerDriver>, null|ReferrerSourceArray> collectDrivers()
 * @method static Collection<class-string<ReferrerSource<mixed>>, ReferrerSource<mixed>> collectSources()
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
