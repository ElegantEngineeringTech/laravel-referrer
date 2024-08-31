<?php

namespace Elegantly\Referrer\Drivers;

use Elegantly\Referrer\ReferrerSources;
use Illuminate\Support\Facades\Context;

/**
 * @phpstan-import-type ReferrerSourceFullArray from ReferrerSources
 */
class ContextDriver extends ReferrerDriver
{
    final public function __construct(
        public string $key,
    ) {
        //
    }

    public static function make(): ?static
    {
        if ($key = static::getKeyFromConfig()) {
            return new static($key);
        }

        return null;
    }

    public static function getKeyFromConfig(): ?string
    {
        /** @var ?string */
        return config('referrer.drivers.'.static::class.'.key');
    }

    public function put(ReferrerSources $sources): void
    {
        Context::add(
            $this->key,
            $sources->toArray()
        );
    }

    public function get(): ?ReferrerSources
    {
        /**
         * @var null|ReferrerSourceFullArray $sources
         */
        $sources = Context::get($this->key);

        if ($sources) {
            return ReferrerSources::fromArray($sources);
        }

        return null;
    }

    public function forget(): void
    {
        Context::forget($this->key);
    }
}
