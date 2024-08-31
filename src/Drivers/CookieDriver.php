<?php

namespace Elegantly\Referrer\Drivers;

use Elegantly\Referrer\ReferrerSources;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;

/**
 * @phpstan-import-type ReferrerSourceFullArray from ReferrerDriver
 */
class CookieDriver extends ReferrerDriver
{
    final public function __construct(
        public string $key,
        public int $lifetime,
    ) {
        //
    }

    public static function make(): ?static
    {
        if ($key = static::getKeyFromConfig()) {
            return new static(
                $key,
                static::getLifetimeFromConfig()
            );
        }

        return null;
    }

    public static function getKeyFromConfig(): ?string
    {
        /** @var ?string */
        return config('referrer.drivers.'.static::class.'.key');
    }

    public static function getLifetimeFromConfig(): int
    {
        /** @var int */
        return config('referrer.drivers.'.static::class.'.lifetime') ?? (60 * 60 * 24 * 30 * 6);
    }

    public function put(ReferrerSources $sources): void
    {
        Cookie::queue(
            new SymfonyCookie(
                name: $this->key,
                value: $sources->toJson(),
                expire: now()->addSeconds($this->lifetime)
            )
        );
    }

    public function get(): ?ReferrerSources
    {
        $cookie = Cookie::get($this->key);

        /**
         * @var null|ReferrerSourceFullArray $sources
         */
        $sources = is_string($cookie) ? json_decode($cookie) : null;

        if ($sources) {
            return ReferrerSources::fromArray($sources);
        }

        return null;
    }

    public function forget(): void
    {
        Cookie::forget($this->key);
    }
}
