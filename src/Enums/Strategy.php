<?php

declare(strict_types=1);

namespace Elegantly\Referrer\Enums;

enum Strategy: string
{
    case First = 'first';
    case Last = 'last';
    case All = 'all';
}
