<?php

namespace Elegantly\Referrer;

use Closure;
use Elegantly\Referrer\Drivers\ReferrerDriver;
use Elegantly\Referrer\Sources\ReferrerSource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @phpstan-import-type ReferrerSourceArray from \Elegantly\Referrer\Drivers\ReferrerDriver
 */
class CaptureReferrerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sources = $this->getReferrerBySource($request);

        if (! empty($sources)) {
            /**
             * @var array<class-string<ReferrerDriver>, mixed> $drivers
             */
            $drivers = config('referrer.drivers') ?? [];

            foreach ($drivers as $driver => $options) {
                $driver::merge($sources);
            }
        }

        return $next($request);
    }

    /**
     * @return ReferrerSourceArray
     */
    public function getReferrerBySource(Request $request): array
    {
        $results = [];

        /**
         * @var class-string<ReferrerSource<mixed>>[]
         */
        $sources = config('referrer.sources') ?? [];

        foreach ($sources as $sourceName) {
            $source = $sourceName::fromRequest($request);

            if (! $source->isEmpty()) {
                $results[$sourceName] = $source;
            }
        }

        return $results;
    }
}
