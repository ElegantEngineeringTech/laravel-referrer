<?php

namespace Elegantly\Referer;

use Closure;
use Elegantly\Referer\Drivers\RefererDriver;
use Elegantly\Referer\Sources\RefererSource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @phpstan-import-type RefererSourceArray from \Elegantly\Referer\Drivers\RefererDriver
 */
class CaptureRefererMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sources = $this->getRefererBySource($request);

        /**
         * @var array<class-string<RefererDriver>, mixed> $drivers
         */
        $drivers = config('referer.drivers');

        foreach ($drivers as $driver => $options) {
            $driver::put($sources);
        }

        return $next($request);
    }

    /**
     * @return RefererSourceArray
     */
    public function getRefererBySource(Request $request): array
    {
        $results = [];

        /**
         * @var class-string<RefererSource<mixed>>[]
         */
        $sources = config('referer.sources');

        foreach ($sources as $source) {
            $results[$source] = $source::fromRequest($request);
        }

        return $results;
    }
}
