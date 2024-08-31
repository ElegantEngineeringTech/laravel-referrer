<?php

namespace Elegantly\Referrer;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Elegantly\Referrer\Sources\ReferrerSource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use IteratorAggregate;
use Traversable;

/**
 * @implements Arrayable<class-string<ReferrerSource<mixed>>, ReferrerSource<mixed>>
 * @implements ArrayAccess<class-string<ReferrerSource<mixed>>, ReferrerSource<mixed>>
 * @implements IteratorAggregate<class-string<ReferrerSource<mixed>>, ReferrerSource<mixed>>
 */
class ReferrerSources implements Arrayable, ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @param  array<class-string<ReferrerSource<mixed>>, ReferrerSource<mixed>>  $items
     */
    final public function __construct(
        public array $items = []
    ) {
        //
        new Collection;
    }

    /**
     * @template T of ReferrerSource<mixed>
     *
     * @param  class-string<T>  $source
     * @return T|null
     */
    public function get(string $source): ?ReferrerSource
    {
        /** @var T|null */
        return $this->items[$source] ?? null;
    }

    /**
     * @param  ReferrerSource<mixed>  $source
     */
    public function put(ReferrerSource $source): static
    {
        $this->items[$source::class] = $source;

        return $this;
    }

    /**
     * @param  class-string<ReferrerSource<mixed>>  $source
     * @return $this
     */
    public function forget(string $source): static
    {
        unset($this->items[$source]);

        return $this;
    }

    public function merge(ReferrerSources $sources): static
    {
        $items = array_merge(
            $this->items,
            $sources->items
        );

        return new static($items);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return \ArrayIterator<class-string<ReferrerSource<mixed>>, ReferrerSource<mixed>>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @template T of ReferrerSource<mixed>
     *
     * @param  class-string<T>|null  $key
     */
    public function offsetExists($key): bool
    {
        return isset($this->items[$key]);
    }

    /**
     * @template T of ReferrerSource<mixed>
     *
     * @param  class-string<T>  $key
     * @return T
     */
    public function offsetGet($key): mixed
    {
        /**
         * @var T
         */
        return $this->items[$key];
    }

    /**
     * @template T of ReferrerSource<mixed>
     *
     * @param  class-string<T>|null  $key
     * @param  T  $value
     */
    public function offsetSet($key, $value): void
    {
        if (is_null($key)) {
            $this->items[$value::class] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    /**
     * @template T of ReferrerSource<mixed>
     *
     * @param  class-string<T>|null  $key
     */
    public function offsetUnset($key): void
    {
        unset($this->items[$key]);
    }
}
