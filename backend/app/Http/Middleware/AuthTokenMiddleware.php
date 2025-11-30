<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthTokenMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $authHeader = $request->header('Authorization');
        $token = null;
        if ($authHeader && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];
        } elseif ($request->has('token')) {
            $token = $request->query('token');
        }

        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token not provided'], 401);
        }

        $user = (new AuthService())->verifyToken($token);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Cek apakah role user sesuai dengan yang diizinkan
        if (!empty($roles) && !in_array($user->role, $roles)) {
            return response()->json(['success' => false, 'message' => 'Forbidden - Insufficient permissions'], 403);
        }

        // Set user ke request untuk digunakan di controller
        $request->merge(['current_user' => $user]);

        return $next($request);
    }
}
