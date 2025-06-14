<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Obtener los roles del usuario de la sesión
        $userRoles = session('usuario_rol', []);

        // Asegurar que tenemos un array
        if (!is_array($userRoles)) {
            $userRoles = [$userRoles];
        }

        // Verificar si el usuario tiene al menos uno de los roles requeridos
        $hasRole = collect($roles)->contains(function ($role) use ($userRoles) {
            return in_array(strtolower($role), array_map('strtolower', $userRoles));
        });

        if (!$hasRole) {
            return redirect()->back()->withErrors(['error' => 'No tienes permiso para acceder a esta sección.']);
        }

        return $next($request);
    }
}
