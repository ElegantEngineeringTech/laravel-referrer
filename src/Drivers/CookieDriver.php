<?php

namespace Elegantly\Referrer\Drivers;

use Elegantly\Referrer\ReferrerSources;
use Illuminate\Support\Facades\Cookie;

/**
 * @phpstan-import-type ReferrerSourcesArray from ReferrerSources
 */
class CookieDriver extends ReferrerDriver
{
    final public function __construct(
        public string $name,
        public int $lifetime,
    ) {
        //
    }

    public static function make(): static
    {
        return new static(
            name: static::getNameFromConfig(),
            lifetime: static::getLifetimeFromConfig()
        );
    }

    public static function getNameFromConfig(): string
    {
        return config()->string('referrer.drivers.'.static::class.'.name');
    }

    public static function getLifetimeFromConfig(): int
    {
        return config()->integer('referrer.drivers.'.static::class.'.lifetime');
    }

    public function put(ReferrerSources $sources): void
    {
        Cookie::queue(
            Cookie::make(
                name: $this->name,
                value: $sources->toJson(),
                minutes: (int) ($this->lifetime / 60)
            )
        );
    }

    public function get(): ?ReferrerSources
    {
        $cookie = Cookie::get($this->name);

        /**
         * @var null|ReferrerSourcesArray $sources
         */
        $sources = is_string($cookie) ? json_decode($cookie, true) : null;

        if ($sources) {
            return ReferrerSources::fromArray($sources);
        }

        return null;
    }

    public function forget(): void
    {
        Cookie::expire($this->name);
    }
}
