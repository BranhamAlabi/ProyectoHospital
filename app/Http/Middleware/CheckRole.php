<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $userRole = session('usuario_rol');
        
        if (!$userRole || !in_array($userRole, $roles)) {
            return redirect()->back()->withErrors('No tienes permisos para realizar esta acción.');
        }

        return $next($request);
    }
}
