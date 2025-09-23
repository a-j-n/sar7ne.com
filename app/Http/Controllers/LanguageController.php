<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switchLanguage(string $locale): RedirectResponse
    {
        $available = array_keys(config('locales.available'));

        if (in_array($locale, $available, true)) {
            Session::put('locale', $locale);

            $minutes = 60 * 24 * 365; // 1 year

            return redirect()->back()->cookie(cookie('locale', $locale, $minutes));
        }

        return redirect()->back();
    }
}
