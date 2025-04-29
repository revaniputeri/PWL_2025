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
        $user_role = $request->user()->getRole();
        $allowedRoles = is_array($roles) ? $roles : explode('|', $roles);
        if (in_array($user_role, $allowedRoles)) {
            return $next($request);
        }
        // Jika tidak memiliki role yang sesuai, tampilkan pesan error
        abort(403, 'Forbidden. Kamu tidak punya akses ke halaman ini');
    }
}
