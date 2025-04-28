<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\ClientAuth;

return Application::configure(basePath: dirname(__DIR__)) // ← ini pakai __DIR__
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ .  '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'client_auth' => \App\Http\Middleware\ClientAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
