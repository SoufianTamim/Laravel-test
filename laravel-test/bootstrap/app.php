<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\VerifyApiToken;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'api.token' => VerifyApiToken::class,
        ]);

        // Change throttle:api to a specific number
        $middleware->group('api', [
            'throttle:60,1', // 60 requests per minute
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
