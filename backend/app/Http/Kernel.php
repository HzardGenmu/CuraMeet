<?php
namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     */
    protected $middleware = [
        \App\Http\Middleware\CorsMiddleware::class,
        \App\Http\Middleware\SecurityHeaders::class,

        // \App\Http\Middleware\TrustProxies::class,
        // \Illuminate\Http\Middleware\HandleCors::class, // Disable, kita pakai custom CORS
        // \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        // \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        // \App\Http\Middleware\TrimStrings::class, // DISABLED - Allow spaces for SQL injection
        // \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class, // DISABLED - Allow empty strings
    ];

    /**
     * The application's route middleware groups.
     */
    protected $middlewareGroups = [

        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * VULNERABILITY NOTES:
     * - 'auth' middleware is WEAK - only checks token validity, no session management
     * - 'is.admin' middleware is WEAK - simple role check, no additional verification
     * - No XSS protection middleware
     * - No input sanitization middleware
     * - No SQL injection protection middleware
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.token' => \App\Http\Middleware\AuthTokenMiddleware::class,
    ];
}
