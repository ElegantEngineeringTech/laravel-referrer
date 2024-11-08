<?php

namespace Elegantly\Referrer\Drivers;

use Elegantly\Referrer\ReferrerSources;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;

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
            new SymfonyCookie(
                name: $this->name,
                value: $sources->toJson(),
                expire: now()->addSeconds($this->lifetime)
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
        Cookie::forget($this->name);
    }
}
