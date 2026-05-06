<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Arr;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     *
     * @throws \Throwable
     */
    public function report(Throwable $e): void
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        return parent::render($request, $e);
    }

    /**
     * Convert unauthenticated request into a redirect or JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $guard = Arr::get($exception->guards(), 0);

        switch ($guard) {
            case 'admin':
                $loginRoute = 'admin.login';
                break;
            default:
                $loginRoute = 'portal';
                break;
        }

        return $this->redirectGuestSafely($request, route($loginRoute));
    }

    /**
     * Like redirect()->guest() but never feeds a broken Referer through UrlGenerator::to()
     * (which can otherwise produce invalid locations such as "http://host/http:").
     *
     * @param  \Illuminate\Http\Request  $request
     */
    protected function redirectGuestSafely($request, string $loginUrl)
    {
        $request->session()->forget('url.intended');

        if (($intended = $this->trustedIntendedUrl($request)) !== null) {
            $request->session()->put('url.intended', $intended);
        }

        return redirect()->to($loginUrl);
    }

    protected function trustedIntendedUrl(HttpRequest $request): ?string
    {
        if ($request->isMethod('GET') && $request->route() && ! $request->expectsJson()) {
            return $request->fullUrl();
        }

        $referer = $request->headers->get('referer');
        if (is_string($referer) && $referer !== '' && $this->isSameApplicationHost($request, $referer)) {
            return $referer;
        }

        $sessionPrevious = $request->session()->previousUrl();
        if (is_string($sessionPrevious) && $sessionPrevious !== '' && $this->isSameApplicationHost($request, $sessionPrevious)) {
            return $sessionPrevious;
        }

        return null;
    }

    protected function isSameApplicationHost(HttpRequest $request, string $candidateUrl): bool
    {
        if (filter_var($candidateUrl, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        $appHost = strtolower((string) parse_url((string) $request->root(), PHP_URL_HOST) ?: '');
        $refHost = strtolower((string) parse_url($candidateUrl, PHP_URL_HOST) ?: '');

        return $appHost !== '' && $refHost !== '' && $appHost === $refHost;
    }
}
