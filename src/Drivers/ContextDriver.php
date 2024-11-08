<?php

namespace Elegantly\Referrer\Drivers;

use Elegantly\Referrer\ReferrerSources;
use Illuminate\Support\Facades\Context;

/**
 * @phpstan-import-type ReferrerSourcesArray from ReferrerSources
 */
class ContextDriver extends ReferrerDriver
{
    final public function __construct(
        public string $key,
    ) {
        //
    }

    public static function make(): static
    {
        return new static(
            key: static::getKeyFromConfig()
        );
    }

    public static function getKeyFromConfig(): string
    {
        return config()->string('referrer.drivers.'.static::class.'.key');
    }

    public function put(ReferrerSources $sources): void
    {
        Context::add(
            key: $this->key,
            value: $sources->toArray()
        );
    }

    public function get(): ?ReferrerSources
    {
        /**
         * @var null|ReferrerSourcesArray $sources
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
