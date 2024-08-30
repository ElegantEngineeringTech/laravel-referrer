<?php

namespace Elegantly\Referer\Sources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;

/**
 * @template TValue
 *
 * @implements Arrayable<string, TValue>
 */
abstract class RefererSource implements Arrayable
{
    abstract public static function fromRequest(Request $request): static;

    /**
     * @param  array<string, TValue>  $values
     */
    abstract public static function fromArray(array $values): static;

    /**
     * @return array<string, TValue>
     */
    abstract public function toArray(): array;
}
