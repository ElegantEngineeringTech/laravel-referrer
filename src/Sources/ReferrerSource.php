<?php

namespace Elegantly\Referrer\Sources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Stringable;

/**
 * @template TValue
 *
 * @implements Arrayable<string, TValue>
 */
abstract class ReferrerSource implements Arrayable, Stringable
{
    public ?float $timestamp = null;

    abstract public static function fromRequest(Request $request): static;

    /**
     * @param  array<string, TValue>  $values
     */
    abstract public static function fromArray(array $values): static;

    abstract public function isEmpty(): bool;

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * @return array<string, TValue>
     */
    abstract public function toArray(): array;

    /**
     * @param  null|string|ReferrerSource<mixed>  $source
     */
    public function is(null|string|ReferrerSource $source): bool
    {
        if (! $source) {
            return false;
        }

        return strval($this) === strval($source);
    }
}
