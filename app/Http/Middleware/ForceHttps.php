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
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('app.env') === 'production' && ! $request->secure()) {
            return redirect()->secure($request->getRequestUri(), 302);
        }

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        return $next($request);
    }
}
