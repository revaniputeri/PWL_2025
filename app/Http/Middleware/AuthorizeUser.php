<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next, $roles): Response
    {
        // Get the user's role
        $user_role = $request->user()->getRole();

        // Always allow ADM (admin) to access any route
        // dd($user_role);
        if ($user_role === 'ADM') {
            return $next($request);
        }

        // For other roles, check specific permissions
        $allowedRoles = is_array($roles) ? $roles : explode(',', $roles);
        if (in_array($user_role, $allowedRoles)) {
            return $next($request);
        }

        // If no matching role, show forbidden message
        abort(403, 'Forbidden. Kamu tidak punya akses ke halaman ini');
    }
}
