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
     * Resolution order: (1) URL built from inbound Host / X-Forwarded-Host so production never
     * follows a stale APP_URL like http://localhost; (2) $request->root(); (3) config('app.url').
     */
    protected function redirectToApplicationPath(Request $request, string $path = '/'): RedirectResponse
    {
        $base = '';
        foreach ($this->applicationRootCandidates($request) as $candidate) {
            if ($candidate !== '' && $this->isValidApplicationRoot($candidate)) {
                $base = $candidate;

                break;
            }
        }

        if ($base === '') {
            $base = rtrim((string) config('app.url'), '/') ?: 'http://localhost';
        }

        $suffix = trim($path, '/');

        if ($suffix !== '') {
            return new RedirectResponse($base.'/'.$suffix);
        }

        return new RedirectResponse($base);
    }

    /**
     * @return list<string>
     */
    private function applicationRootCandidates(Request $request): array
    {
        $fromHeaders = $this->absoluteUrlFromInboundHeaders($request);
        $fromRoot = rtrim((string) $request->root(), '/');
        $fromConfig = rtrim((string) config('app.url'), '/');

        return array_values(array_unique(array_filter([$fromHeaders, $fromRoot, $fromConfig])));
    }

    private function absoluteUrlFromInboundHeaders(Request $request): string
    {
        $host = $this->primaryInboundHost($request);
        if ($host === '') {
            return '';
        }

        $scheme = $request->getScheme();
        if (! in_array($scheme, ['http', 'https'], true)) {
            $scheme = $request->secure() ? 'https' : 'http';
        }

        $pathPrefix = $request->getBaseUrl() ?: '';

        return rtrim($scheme.'://'.$host.$pathPrefix, '/');
    }

    private function primaryInboundHost(Request $request): string
    {
        $forwarded = $request->headers->get('X-Forwarded-Host');
        if (is_string($forwarded) && $forwarded !== '') {
            $forwarded = trim(explode(',', $forwarded, 2)[0]);

            return $this->normalizeHostHeaderValue($forwarded);
        }

        $host = $request->headers->get('Host');
        if (is_string($host) && $host !== '') {
            return $this->normalizeHostHeaderValue(trim(explode(',', $host, 2)[0]));
        }

        return '';
    }

    private function normalizeHostHeaderValue(string $host): string
    {
        $host = trim($host);
        if ($host === '') {
            return '';
        }

        // Strip IPv6 brackets if present (Host is usually hostname:port).
        if (str_starts_with($host, '[')) {
            $end = strpos($host, ']');
            if ($end !== false) {
                return substr($host, 1, $end - 1);
            }
        }

        return $host;
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
