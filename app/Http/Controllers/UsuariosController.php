<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    // Mostrar formulario para crear usuario
    public function create()
    {
        $rolActual = session('usuario_rol');

        // For testing purposes, let's assume we're a moderador if no session is set
        // In production, this should be properly handled by authentication middleware
        if ($rolActual === 'moderador' || !$rolActual) {
            $roles = Rol::whereNotIn('nombre', ['admin', 'moderador'])
                        ->orderBy('nombre')
                        ->get();
        } else {
            $roles = Rol::orderBy('nombre')->get();
        }

        return view('usuarios.form', compact('roles'));
    }

    // Guardar nuevo usuario
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|min:8',
            'rol_id' => 'required|exists:roles,id',
            'estado' => 'nullable|in:activo,inactivo',
        ]);

        $usuario = new Usuarios();
        $usuario->nombre = $validated['nombre'];
        $usuario->correo = $validated['correo'];
        $usuario->estado = $validated['estado'] ?? 'activo'; // Por defecto activo
        $usuario->contrasena = bcrypt($validated['password']);
        $usuario->save();

        $usuario->roles()->sync([$validated['rol_id']]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    // Mostrar detalle de usuario
    public function show($id)
    {
        $usuario = Usuarios::with('roles')->findOrFail($id);
        return view('usuarios.show', compact('usuario'));
    }

    // Mostrar formulario para editar usuario
    public function edit($id)
    {
        $usuario = Usuarios::with('roles')->findOrFail($id);

        $rolActual = session('usuario_rol');

        if ($rolActual === 'moderador') {
            $rolesUsuario = $usuario->roles->pluck('nombre')->toArray();
            if (in_array('admin', $rolesUsuario) || in_array('moderador', $rolesUsuario)) {
                return redirect()->route('usuarios.index')->withErrors('No tienes permiso para editar este usuario.');
            }

            $roles = Rol::whereNotIn('nombre', ['admin', 'moderador'])
                        ->orderBy('nombre')
                        ->get();
        } else {
            $roles = Rol::orderBy('nombre')->get();
        }

        return view('usuarios.form', compact('usuario', 'roles'));
    }

    // Actualizar usuario
    public function update(Request $request, $id)
    {
        $usuario = Usuarios::with('roles')->findOrFail($id);

        $rolActual = session('usuario_rol');

        if ($rolActual === 'moderador') {
            $rolesUsuario = $usuario->roles->pluck('nombre')->toArray();
            if (in_array('administrador', $rolesUsuario) || in_array('moderador', $rolesUsuario)) {
                return redirect()->route('usuarios.index')->withErrors('No tienes permiso para actualizar este usuario.');
            }
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'correo' => ['required', 'email', Rule::unique('usuarios', 'correo')->ignore($usuario->id)],
            'rol_id' => 'required|exists:roles,id',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $usuario->nombre = $validated['nombre'];
        $usuario->correo = $validated['correo'];
        $usuario->estado = $validated['estado'];
        $usuario->save();

        $usuario->roles()->sync([$validated['rol_id']]);

        return redirect()->route('usuarios.show', $usuario->id)->with('success', 'Usuario actualizado exitosamente.');
    }

}
