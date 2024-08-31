<?php

namespace Elegantly\Referrer\Drivers;

use Elegantly\Referrer\ReferrerSources;
use Illuminate\Support\Facades\Session;

/**
 * @phpstan-import-type ReferrerSourceFullArray from ReferrerSources
 */
class SessionDriver extends ReferrerDriver
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
        Session::put(
            $this->key,
            $sources->toArray()
        );
    }

    public function get(): ?ReferrerSources
    {
        /**
         * @var null|ReferrerSourceFullArray $sources
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
