<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(ResponseFactory $response): Response
    {
        $urls = [];
        $base = config('app.url');
        $locales = (array) config('app.supported_locales', ['en', 'ar']);
        $param = (string) config('app.locale_param', 'lang');

        // Static pages
        $statics = [
            route('explore'),
        ];

        foreach ($statics as $u) {
            foreach ($locales as $loc) {
                $urls[] = [
                    'loc' => $u.'?'.$param.'='.$loc,
                    'changefreq' => 'daily',
                    'priority' => '0.8',
                ];
            }
        }

        // Public profiles
        User::query()
            ->select(['id', 'username', 'updated_at'])
            ->orderByDesc('id')
            ->limit(5000)
            ->each(function (User $u) use (&$urls, $locales, $param) {
                $locBase = route('profile.public', ['user' => $u->username]);
                foreach ($locales as $loc) {
                    $urls[] = [
                        'loc' => $locBase.'?'.$param.'='.$loc,
                        'lastmod' => optional($u->updated_at)->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.7',
                    ];
                }
            });

        // Timeline feature removed

        $xml = view('sitemap.xml', ['urls' => $urls])->render();

        return $response->make($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
