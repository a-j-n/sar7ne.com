<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment(['local', 'development'])) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('message-submission', function (Request $request) {
            $username = (string) ($request->route('username')
                ?? optional($request->route('user'))->username
                ?? $request->input('username', 'public'));

            $ip = $request->ip() ?? 'unknown';

            return [
                Limit::perMinute(5)->by($ip.'|'.$username),
                Limit::perHour(40)->by($ip.'|'.$username),
            ];
        });
    }
}
