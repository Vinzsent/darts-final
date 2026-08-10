<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userType = Auth::user()->user_type;
        $normalized = strtolower(str_replace(' ', '-', $userType ?? ''));

        foreach ($roles as $role) {
            $normalizedRole = strtolower(str_replace(' ', '-', $role));
            if ($normalized === $normalizedRole) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
