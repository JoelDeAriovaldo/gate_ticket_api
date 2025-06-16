<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * RoleMiddleware
 *
 * Handles role-based access control for routes.
 * Checks the authenticated user's role against the required role.
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user || ($user->role->value ?? $user->role) !== $role) {
            return response()->json([
                'message' => 'Insufficient permissions',
                'success' => false,
            ], 403);
        }

        return $next($request);
    }
}
