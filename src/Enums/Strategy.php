<?php

namespace Elegantly\Referrer\Enums;

enum Strategy: string
{
    case First = 'first';
    case Last = 'last';
    case All = 'all';
}
