<?php

namespace Elegantly\Referrer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @phpstan-import-type ReferrerSourceArray from \Elegantly\Referrer\Drivers\ReferrerDriver
 * @phpstan-import-type ReferrerDriverArray from \Elegantly\Referrer\Referrer
 *
 * @method static ReferrerDriverArray getSourcesByDriver()
 * @method static ReferrerSourceArray getSources()
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
