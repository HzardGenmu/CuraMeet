<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AuthService;

class Authenticate
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Extract token dari Authorization header
        $token = $this->extractToken($request);

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not provided'
            ], 401);
        }

        // Verify token menggunakan AuthService
        $user = $this->authService->verifyToken($token);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token'
            ], 401);
        }

        // Set user ke request untuk digunakan di controller
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        // Juga set ke Auth facade (opsional, untuk kompatibilitas)
        auth()->setUser($user);

        return $next($request);
    }

    /**
     * Extract token dari request
     */
    private function extractToken(Request $request)
    {
        // 1. Cek Authorization header (Bearer token)
        $authHeader = $request->header('Authorization');
        if ($authHeader && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }

        // 2. Cek query parameter ?token=xxx
        if ($request->has('token')) {
            return $request->query('token');
        }

        return null;
    }
}
