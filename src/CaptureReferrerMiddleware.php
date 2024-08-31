<?php

namespace Elegantly\Referrer;

use Closure;
use Elegantly\Referrer\Facades\Referrer;
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

        if ($sources->count()) {
            Referrer::put($sources);
        }

        return $next($request);
    }

    public function getReferrerBySource(Request $request): ReferrerSources
    {
        $items = new ReferrerSources;

        /**
         * @var class-string<ReferrerSource<mixed>>[]
         */
        $sources = config('referrer.sources') ?? [];

        foreach ($sources as $sourceName) {
            $source = $sourceName::fromRequest($request);

            if (! $source->isEmpty()) {
                $items->put($source);
            }
        }

        return $items;
    }
}
