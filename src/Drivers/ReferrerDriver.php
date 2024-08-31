<?php

namespace Elegantly\Referrer\Drivers;

use Elegantly\Referrer\ReferrerSources;
use Elegantly\Referrer\Sources\ReferrerSource;

/**
 * @phpstan-type ReferrerSourceFullArray array<class-string<ReferrerSource<mixed>>, array<string, mixed>>
 * @phpstan-type ReferrerSourceArray array<class-string<ReferrerSource<mixed>>, ReferrerSource<mixed>>
 */
abstract class ReferrerDriver
{
    abstract public static function make(): ?static;

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
