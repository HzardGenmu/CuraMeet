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
}
