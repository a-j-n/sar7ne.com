<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switchLanguage(string $locale, Request $request): RedirectResponse
    {
        $available = array_keys(config('locales.available'));

        if (in_array($locale, $available, true)) {
            Session::put('locale', $locale);
            // Force session write so subsequent redirect request can read it immediately
            Session::save();

            $minutes = 60 * 24 * 365; // 1 year

            // Create cookie with explicit attributes. secure when the request is HTTPS.
            $secure = $request->isSecure();
            $cookie = cookie(name: 'locale', value: $locale, minutes: $minutes, path: '/', domain: null, secure: $secure, httpOnly: false, raw: false, sameSite: 'Lax');

            return redirect()->back()->withCookie($cookie);
        }

        return redirect()->back();
    }
}
