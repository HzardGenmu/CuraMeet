<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Add custom CORS middleware
        \App\Http\Middleware\CorsMiddleware::class,
        // ... other middleware
    ];

    protected $middlewareGroups = [
        'api' => [
            // Remove HandleCors to avoid duplication
            // \Illuminate\Http\Middleware\HandleCors::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * @var array
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        // ... alias bawaan Laravel lainnya

        // PINDAHKAN is.admin DARI $middlewareGroups KE SINI
        'is.admin' => \App\Http\Middleware\IsAdminMiddleware::class,
    ];
}
