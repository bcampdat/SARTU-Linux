<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RolMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->rol || !in_array($user->rol->nombre, $roles)) {
            return abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
