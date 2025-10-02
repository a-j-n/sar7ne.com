<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Ensure the SetLocaleFromRequest runs for web requests after cookie/session middleware
        $middleware->appendToGroup('web', \App\Http\Middleware\SetLocaleFromRequest::class);
        // Force HTTPS and www. subdomain
        $middleware->prependToGroup('web', \App\Http\Middleware\ForceHttpsWww::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// Register the new SiteMapGenerator command
// \App\Console\Commands\SiteMapGenerator::class
