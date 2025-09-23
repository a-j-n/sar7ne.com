<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpsAndWww
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $isWww = str_starts_with($host, 'www.');
        $isSecure = $request->isSecure();
        $baseDomain = config('app.domain', 'sar7ne.com');
        $targetScheme = 'https';
        $uri = $request->getRequestUri();

        // Match user subdomains, with or without www.
        if (preg_match('/^(?:www\\.)?([a-zA-Z0-9_-]+)\\.' . preg_quote($baseDomain) . '$/', $host, $matches) && $matches[1] !== 'www') {
            $username = $matches[1];
            $redirectUrl = $targetScheme . '://www.' . $baseDomain . '/' . ltrim($username . $uri, '/');
            return redirect()->to($redirectUrl, 301);
        }

        // Only prepend www. for the base domain
        if ($host === $baseDomain || $host === 'www.' . $baseDomain) {
            $targetHost = 'www.' . $baseDomain;
            if (!$isSecure || $host !== $targetHost) {
                $redirectUrl = $targetScheme . '://' . $targetHost . $uri;
                return redirect()->to($redirectUrl, 301);
            }
        }

        return $next($request);
    }
}
