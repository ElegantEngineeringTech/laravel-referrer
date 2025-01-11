<?php

declare(strict_types=1);

namespace Elegantly\Referrer\Drivers;

use Elegantly\Referrer\ReferrerSources;
use Illuminate\Support\Facades\Session;

/**
 * @phpstan-import-type ReferrerSourcesArray from ReferrerSources
 */
class SessionDriver extends ReferrerDriver
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
        Session::put(
            $this->key,
            $sources->toArray()
        );
    }

    public function get(): ?ReferrerSources
    {
        /**
         * @var null|ReferrerSourcesArray $sources
         */
        $sources = Session::get($this->key);

        if ($sources) {
            return ReferrerSources::fromArray($sources);
        }

        return null;
    }

    public function forget(): void
    {
        Session::forget($this->key);
    }
}
