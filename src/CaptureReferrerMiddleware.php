<?php

namespace Elegantly\Referrer;

use Closure;
use Elegantly\Referrer\Facades\Referrer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $items = Referrer::getSources();

        $sources = Referrer::getSourcesFromConfig();

        foreach ($sources as $sourceName) {
            $strategy = Referrer::getStrategy($sourceName);
            $source = $sourceName::fromRequest($request);

            if (
                $source->isNotEmpty() &&
                ! $items->has($source)
            ) {
                $items->put(
                    source: $source,
                    strategy: $strategy
                );
            }
        }

        return $items;
    }
}
