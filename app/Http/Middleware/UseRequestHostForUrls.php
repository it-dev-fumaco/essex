<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/**
 * When the request host is 127.0.0.1 or localhost, force Laravel to generate
 * all URLs (links, redirects, assets) using that host instead of APP_URL.
 * Fixes "connection refused" when APP_URL is essex.local but user visits via 127.0.0.1.
 */
class UseRequestHostForUrls
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();

        if (in_array($host, ['127.0.0.1', 'localhost'], true)) {
            $rootUrl = $request->getScheme() . '://' . $request->getHttpHost();
            URL::forceRootUrl($rootUrl);
        }

        return $next($request);
    }
}
