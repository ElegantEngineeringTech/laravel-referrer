<?php

namespace Elegantly\Referrer;

use ArrayIterator;
use Countable;
use Elegantly\Referrer\Enums\Strategy;
use Elegantly\Referrer\Sources\ReferrerSource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;
use IteratorAggregate;
use Traversable;

/**
 * @phpstan-type ReferrerSourcesArray array< class-string<ReferrerSource<mixed>> , array<int, mixed[]> >
 * @phpstan-type ReferrerSourcesItems array< class-string<ReferrerSource<mixed>> , array<int, ReferrerSource<mixed>> >
 *
 * @implements Arrayable< class-string<ReferrerSource<mixed>>, array<int, mixed[]> >
 * @implements IteratorAggregate< class-string<ReferrerSource<mixed>> , array<int, ReferrerSource<mixed>> >
 */
class ReferrerSources implements Arrayable, Countable, IteratorAggregate, Jsonable
{
    /**
     * @param  ReferrerSourcesItems  $items
     */
    final public function __construct(
        public array $items = []
    ) {
        //
    }

    /**
     * @template T of ReferrerSource<mixed>
     *
     * @param  class-string<T>  $source
     * @return array<int, T>
     */
    public function get(string $source): array
    {
        /** @var array<int, T> */
        $values = $this->items[$source] ?? [];

        return $values;
    }

    /**
     * @param  class-string<ReferrerSource<mixed>>|ReferrerSource<mixed>  $source
     */
    public function has(
        string|ReferrerSource $source,
    ): bool {

        if ($source instanceof ReferrerSource) {
            return (bool) Arr::first(
                $this->get($source::class),
                fn ($value) => $value->is($source)
            );
        }

        return ! empty($this->get($source));
    }

    /**
     * @template T of ReferrerSource<mixed>
     *
     * @param  class-string<T>  $source
     * @return T|null
     */
    public function getFirst(string $source): ?ReferrerSource
    {
        $values = $this->get($source);

        $key = array_key_first($values);

        return $values[$key] ?? null;
    }

    /**
     * @template T of ReferrerSource<mixed>
     *
     * @param  class-string<T>  $source
     * @return T|null
     */
    public function getLast(string $source): ?ReferrerSource
    {
        $values = $this->get($source);

        $key = array_key_last($values);

        return $values[$key] ?? null;
    }

    /**
     * Oldest source based on timestamp
     *
     * @return null|ReferrerSource<mixed>
     */
    public function getOldest(): ?ReferrerSource
    {
        /** @var null|ReferrerSource<mixed> $oldest */
        $oldest = null;

        foreach ($this as $values) {
            foreach ($values as $value) {

                if (! $oldest) {
                    $oldest = $value;
                } elseif (
                    ! $oldest->timestamp &&
                    $value->timestamp
                ) {
                    $oldest = $value;
                } elseif (
                    $oldest->timestamp &&
                    $value->timestamp &&
                    $value->timestamp < $oldest->timestamp
                ) {
                    $oldest = $value;
                }

            }
        }

        return $oldest;
    }

    /**
     * Most recent source based on timestamp
     *
     * @return null|ReferrerSource<mixed>
     */
    public function getLatest(): ?ReferrerSource
    {
        /** @var null|ReferrerSource<mixed> $latest */
        $latest = null;

        foreach ($this as $values) {
            foreach ($values as $value) {

                if (! $latest) {
                    $latest = $value;
                } elseif (
                    ! $latest->timestamp &&
                    $value->timestamp
                ) {
                    $latest = $value;
                } elseif (
                    $latest->timestamp &&
                    $value->timestamp &&
                    $value->timestamp >= $latest->timestamp
                ) {
                    $latest = $value;
                }

            }
        }

        return $latest;
    }

    /**
     * @param  ReferrerSource<mixed>  $source
     * @return $this
     */
    public function replace(ReferrerSource $source): static
    {
        $this->items[$source::class] = [$source];

        return $this;
    }

    /**
     * @param  ReferrerSource<mixed>  $source
     * @return $this
     */
    public function add(ReferrerSource $source): static
    {
        $this->items[$source::class] ??= [];

        $this->items[$source::class][] = $source;

        return $this;
    }

    /**
     * @param  ReferrerSource<mixed>  $source
     */
    public function put(
        ReferrerSource $source,
        Strategy $strategy,
    ): static {

        if ($strategy === Strategy::Last) {
            return $this->replace($source);
        }

        if (
            $strategy === Strategy::First &&
            $this->has($source::class)
        ) {
            return $this;
        }

        return $this->add($source);
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

    public function merge(?ReferrerSources $sources): static
    {
        if (! $sources) {
            return new static($this->items);
        }

        $items = new static($this->items);

        foreach ($sources as $values) {
            foreach ($values as $source) {

                if (! $items->has($source)) {
                    $items->add($source);
                }

            }
        }

        return $items;
    }

    /**
     * @return ReferrerSourcesItems
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @return ReferrerSourcesArray
     */
    public function toArray(): array
    {
        return array_map(function ($item) {

            return array_map(fn ($value) => $value->toArray(), $item);

        }, $this->items);
    }

    public function toJson($options = 0): string
    {
        return (string) json_encode($this->toArray(), $options);
    }

    /**
     * @param  ReferrerSourcesArray  $sources
     */
    public static function fromArray(array $sources): static
    {
        $items = new static;

        foreach ($sources as $source => $values) {

            foreach ($values as $value) {
                $items->add($source::fromArray($value));
            }

        }

        return $items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return \ArrayIterator< class-string<ReferrerSource<mixed>>, array<int, ReferrerSource<mixed>> >
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
