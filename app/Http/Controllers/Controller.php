<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Redirect using the request's application root (scheme + host + base path).
     * Avoids UrlGenerator::previous()/Referer edge cases that can produce paths like "/http:".
     *
     * When proxy/forwarded headers are wrong, $request->root() can degrade to values like "http:"
     * (no host). Fall back to APP_URL so Location headers stay valid.
     */
    protected function redirectToApplicationPath(Request $request, string $path = '/'): RedirectResponse
    {
        $fromRequest = rtrim((string) $request->root(), '/');
        $base = $this->isValidApplicationRoot($fromRequest)
            ? $fromRequest
            : rtrim((string) config('app.url'), '/');

        $suffix = trim($path, '/');

        if ($suffix !== '') {
            return new RedirectResponse($base.'/'.$suffix);
        }

        return new RedirectResponse($base);
    }

    private function isValidApplicationRoot(string $root): bool
    {
        if ($root === '') {
            return false;
        }

        if (filter_var($root, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        $parts = parse_url($root);

        return isset($parts['scheme'], $parts['host'])
            && in_array($parts['scheme'], ['http', 'https'], true)
            && $parts['host'] !== '';
    }
}
