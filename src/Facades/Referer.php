<?php

namespace Elegantly\Referer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @phpstan-import-type RefererSourceArray from \Elegantly\Referer\Drivers\RefererDriver
 * @phpstan-import-type RefererDriverArray from \Elegantly\Referer\Referer
 *
 * @method static RefererDriverArray getSourcesByDriver()
 * @method static RefererSourceArray getSources()
 *
 * @see \Elegantly\Referer\Referer
 */
class Referer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Elegantly\Referer\Referer::class;
    }
}
