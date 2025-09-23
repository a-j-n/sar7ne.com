<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $available = array_keys(config('locales.available', ['ar' => []]));
        $default = config('locales.default', config('app.locale'));

        // Prefer session, then cookie, then app/default
        $locale = Session::get('locale') ?: $request->cookie('locale') ?: $default;

        // Validate allowed locales
        if (! in_array($locale, $available, true)) {
            $locale = $default;
        }

        // Persist to session for quick access
        Session::put('locale', $locale);

        App::setLocale($locale);

        return $next($request);
    }
}
