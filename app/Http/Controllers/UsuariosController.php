<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Rol;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function index(Request $request)
    {
        $query = Usuarios::query();

        // Join con roles para filtrar por rol
        if ($request->filled('rol')) {
            $rolNombre = $request->input('rol');
            $query->whereHas('roles', function ($q) use ($rolNombre) {
                $q->where('nombre', $rolNombre);
            });
        }

        // Filtrar por estado
        if ($request->filled('estado')) {
            $estado = $request->input('estado');
            $query->where('estado', $estado);
        }

        // Buscar por nombre o correo (texto)
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('correo', 'like', "%{$buscar}%");
            });
        }

        // Ordenar por nombre
        $usuarios = $query->with('roles')->orderBy('nombre')->paginate(10)->withQueryString();

        // Para el filtro, obtener lista de roles (para select)
        $roles = Rol::orderBy('nombre')->pluck('nombre');

        return view('usuarios.index', compact('usuarios', 'roles'));
    }
}
