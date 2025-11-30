<?php

use App\Http\Middleware\SecureFileAccess;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // run CORS before everything else
        // $middleware->prepend(HandleCors::class);

        // Daftarkan middleware alias di sini
        $middleware->alias([
            'auth.token' => \App\Http\Middleware\AuthTokenMiddleware::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
            'file-access' => SecureFileAccess::class
        ]);

        // Global middleware
        $middleware->use([
            \App\Http\Middleware\CorsMiddleware::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
