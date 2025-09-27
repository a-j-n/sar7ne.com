<?php

use App\Http\Middleware\ForceHttpsWww;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

test('middleware redirects http to https in production', function () {
    // Mock production environment
    App::shouldReceive('environment')->with('local')->andReturn(false);

    $request = Request::create('http://www.sar7ne.com/', 'GET');
    $middleware = new ForceHttpsWww;

    $response = $middleware->handle($request, function () {
        return response('OK');
    });

    expect($response->getStatusCode())->toBe(301);
    expect($response->getTargetUrl())->toBe('https://www.sar7ne.com/');
});

test('middleware redirects non-www to www in production', function () {
    // Mock production environment
    App::shouldReceive('environment')->with('local')->andReturn(false);

    $request = Request::create('https://sar7ne.com/', 'GET');
    $middleware = new ForceHttpsWww;

    $response = $middleware->handle($request, function () {
        return response('OK');
    });

    expect($response->getStatusCode())->toBe(301);
    expect($response->getTargetUrl())->toBe('https://www.sar7ne.com/');
});

test('middleware redirects http non-www to https www in production', function () {
    // Mock production environment
    App::shouldReceive('environment')->with('local')->andReturn(false);

    $request = Request::create('http://sar7ne.com/', 'GET');
    $middleware = new ForceHttpsWww;

    $response = $middleware->handle($request, function () {
        return response('OK');
    });

    expect($response->getStatusCode())->toBe(301);
    expect($response->getTargetUrl())->toBe('https://www.sar7ne.com/');
});

test('middleware does not redirect localhost or test domains', function () {
    // Mock production environment but test with localhost
    App::shouldReceive('environment')->with('local')->andReturn(false);

    $request = Request::create('http://localhost/', 'GET');
    $middleware = new ForceHttpsWww;

    $response = $middleware->handle($request, function () {
        return response('OK');
    });

    // Should only redirect to HTTPS, not add www to localhost
    expect($response->getStatusCode())->toBe(301);
    expect($response->getTargetUrl())->toBe('https://localhost/');
});

test('middleware preserves query parameters and paths during redirect', function () {
    // Mock production environment
    App::shouldReceive('environment')->with('local')->andReturn(false);

    $request = Request::create('http://sar7ne.com/explore?q=test', 'GET');
    $middleware = new ForceHttpsWww;

    $response = $middleware->handle($request, function () {
        return response('OK');
    });

    expect($response->getStatusCode())->toBe(301);
    expect($response->getTargetUrl())->toBe('https://www.sar7ne.com/explore?q=test');
});
