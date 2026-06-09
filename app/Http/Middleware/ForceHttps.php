<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/**
 * Force HTTPS in production (replacement for ecrmnn/laravel-https).
 * When upgrading, remove ecrmnn/laravel-https from composer and from config/app.php providers.
 */
class ForceHttps
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->secure()) {
            $root = $request->getScheme().'://'.$request->getHttpHost();
            URL::forceRootUrl($root);
            URL::forceScheme('https');
        }

        if (! config('app.force_https', false)) {
            return $next($request);
        }

        if (config('app.env') === 'production' && ! $request->secure()) {
            // Behind TLS-terminating proxies that omit X-Forwarded-Proto, forcing HTTPS
            // would redirect to the same URL indefinitely (ERR_TOO_MANY_REDIRECTS).
            $forwardedProto = $request->headers->get('X-Forwarded-Proto');
            if ($forwardedProto === null || $forwardedProto === '') {
                URL::forceScheme('https');

                return $next($request);
            }

            if ($forwardedProto !== 'https') {
                return redirect()->secure($request->getRequestUri(), 302);
            }
        }

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        return $next($request);
    }
}
