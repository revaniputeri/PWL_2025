<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeStaff
{
    /**
     * Handle an incoming request, allowing all authenticated roles (Admin, Manager, Staff).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the user's role
        $user_role = $request->user()->getRole();

        // Allow ADM, MNG, and STF to access
        if (in_array($user_role, ['ADM', 'MNG', 'STF'])) {
            return $next($request);
        }

        // If not an allowed role, show forbidden message
        abort(403, 'Forbidden. Kamu tidak memiliki akses ke halaman ini');
    }
}