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

        
        $map = [
            'admin_sistema' => 1,
            'encargado'     => 2,
            'empleado'      => 3,
        ];

        foreach ($roles as $rol) {
            if (isset($map[$rol]) && $user->rol_id == $map[$rol]) {
                return $next($request);
            }
        }

        return abort(403, 'No tienes permisos para acceder a esta sección.');
    }
}
