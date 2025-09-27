<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpsWww
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip redirects in local development
        if (app()->environment('local')) {
            return $next($request);
        }

        $host = $request->getHost();
        $scheme = $request->getScheme();
        $needsRedirect = false;

        // Force HTTPS
        if ($scheme !== 'https') {
            $needsRedirect = true;
            $scheme = 'https';
        }

        // Force www subdomain - only for production domains
        if (! str_starts_with($host, 'www.') && ! str_contains($host, '.test') && $host !== 'localhost' && ! str_starts_with($host, '127.0.0.1')) {
            $needsRedirect = true;
            $host = 'www.'.$host;
        }

        // Perform redirect if needed
        if ($needsRedirect) {
            $newUrl = $scheme.'://'.$host.$request->getRequestUri();

            return redirect($newUrl, 301);
        }

        return $next($request);
    }
}
