<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class ThemeController extends Controller
{
    public function switchTheme(string $theme, Request $request): RedirectResponse
    {
        $available = ['light', 'dark'];

        if (! in_array($theme, $available, true)) {
            return redirect()->back();
        }

        // Persist to cookie so server-rendered pages can pick it up
        $minutes = 60 * 24 * 365;
        $secure = $request->isSecure();
        $cookie = cookie(name: 'theme', value: $theme, minutes: $minutes, path: '/', domain: null, secure: $secure, httpOnly: false, raw: false, sameSite: 'Lax');

        return redirect()->back()->withCookie($cookie);
    }
}

