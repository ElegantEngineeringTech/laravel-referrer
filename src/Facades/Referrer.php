<?php

namespace Elegantly\Referrer\Facades;

use Elegantly\Referrer\Drivers\ReferrerDriver;
use Elegantly\Referrer\ReferrerSources;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array<class-string<ReferrerDriver>, ReferrerSources> getSourcesByDriver()
 * @method static ReferrerSources get()
 * @method static void put(ReferrerSources $sources)
 * @method static void forget()
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
