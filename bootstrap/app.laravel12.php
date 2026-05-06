<?php

declare(strict_types=1);

/**
 * Laravel 12 bootstrap. Replace bootstrap/app.php with this file's content
 * once the application is upgraded to Laravel 12 (see UPGRADE_LARAVEL_5_TO_12.md).
 *
 * Keeps same route URLs and middleware by loading web/api routes with
 * App\Http\Controllers namespace and registering middleware aliases.
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        then: function (): void {
            Route::middleware('web')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/web.php'));
            Route::prefix('api')
                ->middleware('api')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\ActivityByUser::class,
        ]);
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            $guard = \Illuminate\Support\Arr::get($e->guards(), 0);
            $loginRoute = $guard === 'admin' ? 'admin.login' : 'portal';
            $loginUrl = route($loginRoute);

            $request->session()->forget('url.intended');
            $intended = null;
            if ($request->isMethod('GET') && $request->route() && ! $request->expectsJson()) {
                $intended = $request->fullUrl();
            } elseif ($referer = $request->headers->get('referer')) {
                if (is_string($referer) && $referer !== '' && filter_var($referer, FILTER_VALIDATE_URL)) {
                    $appHost = strtolower((string) parse_url((string) $request->root(), PHP_URL_HOST) ?: '');
                    $refHost = strtolower((string) parse_url($referer, PHP_URL_HOST) ?: '');
                    if ($appHost !== '' && $refHost !== '' && $appHost === $refHost) {
                        $intended = $referer;
                    }
                }
            }
            $sessionPrev = $request->session()->previousUrl();
            if ($intended === null && is_string($sessionPrev) && $sessionPrev !== '' && filter_var($sessionPrev, FILTER_VALIDATE_URL)) {
                $appHost = strtolower((string) parse_url((string) $request->root(), PHP_URL_HOST) ?: '');
                $refHost = strtolower((string) parse_url($sessionPrev, PHP_URL_HOST) ?: '');
                if ($appHost !== '' && $refHost !== '' && $appHost === $refHost) {
                    $intended = $sessionPrev;
                }
            }
            if ($intended !== null) {
                $request->session()->put('url.intended', $intended);
            }

            return redirect()->to($loginUrl);
        });
    })
    ->create();
