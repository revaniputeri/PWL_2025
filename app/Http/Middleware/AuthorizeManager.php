<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeManager
{
    /**
     * Handle an incoming request, allowing only Managers and Admins.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the user's role
        $user_role = $request->user()->getRole();

        // Allow ADM (admin) and MNG (manager) to access
        if ($user_role === 'ADM' || $user_role === 'MNG') {
            return $next($request);
        }

        // If not admin or manager, show forbidden message
        abort(403, 'Forbidden. Hanya Admin dan Manager yang dapat mengakses halaman ini');
    }
}