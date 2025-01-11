<?php

declare(strict_types=1);

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
        $sources = Referrer::getSources();

        $updated = false;

        foreach (Referrer::getSourcesFromConfig() as $sourceName) {
            $strategy = Referrer::getStrategy($sourceName);
            $source = $sourceName::fromRequest($request);

            if ($source->isEmpty()) {
                continue;
            }

            if ($sources->has($source)) {
                continue;
            }

            $sources->put(
                source: $source,
                strategy: $strategy
            );

            $updated = true;
        }

        if ($updated) {
            Referrer::put($sources);
        }

        return $next($request);
    }
}
