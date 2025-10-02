<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Cookie\QueueingFactory as CookieQueue;
use Illuminate\Http\Request;

class SetLocaleFromRequest
{
    public function __construct(public CookieQueue $cookies) {}

    public function handle(Request $request, Closure $next)
    {
        $supported = (array) config('app.supported_locales', ['en', 'ar']);
        $param = (string) config('app.locale_param', 'lang');

        $locale = $request->query($param) ?? $request->cookie('locale');
        if (is_string($locale) && in_array($locale, $supported, true)) {
            app()->setLocale($locale);
            // persist for 1 year
            $this->cookies->queue(cookie('locale', $locale, 60 * 24 * 365));
        }

        return $next($request);
    }
}
