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
        $aliases = config('locales.aliases', []);

        $normalize = static function (?string $value) use ($aliases) {
            if (! $value) {
                return null;
            }

            if (isset($aliases[$value])) {
                return $aliases[$value];
            }

            $lower = str_replace('-', '_', strtolower($value));

            return $aliases[$lower] ?? $value;
        };

        $default = $normalize(config('locales.default', config('app.locale'))) ?? 'ar';

        // Prefer session, then cookie, then app/default
        $locale = $normalize(Session::get('locale'))
            ?: $normalize($request->cookie('locale'))
            ?: $default;

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
