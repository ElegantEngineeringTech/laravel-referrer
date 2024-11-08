<?php

namespace Elegantly\Referrer\Drivers;

use Elegantly\Referrer\ReferrerSources;

abstract class ReferrerDriver
{
    abstract public static function make(): static;

    abstract public function put(ReferrerSources $sources): void;

    abstract public function get(): ?ReferrerSources;

    abstract public function forget(): void;

    public function merge(ReferrerSources $sources): void
    {
        if ($current = $this->get()) {
            $this->put($current->merge($sources));
        } else {
            $this->put($sources);
        }
    }
}
