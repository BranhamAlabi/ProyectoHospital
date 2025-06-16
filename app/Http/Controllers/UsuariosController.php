<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class UsuariosController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Si es administrador, permitir todo
            if (session('usuario_rol') === 'administrador') {
                return $next($request);
            }
            
            // Para otros roles, aplicar restricciones
            if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')) {
                if ($request->has('id')) {
                    $usuario = Usuarios::find($request->id);
                    if ($usuario && $usuario->roles->pluck('nombre')->intersect(['administrador', 'moderador'])->isNotEmpty()) {
                        return redirect()->route('usuarios.index')
                            ->withErrors('No tienes permisos para modificar administradores o moderadores.');
                    }
                }
                if ($request->has('roles') && collect($request->roles)->intersect(['administrador', 'moderador'])->isNotEmpty()) {
                    return redirect()->route('usuarios.index')
                        ->withErrors('No tienes permisos para asignar roles de administrador o moderador.');
                }
            }
            
            return $next($request);
        });
    }

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
        $userRoles = session('usuario_rol', []);
        
        // Convertir a array si es string
        if (is_string($userRoles)) {
            $userRoles = [$userRoles];
        }

        // Si es administrador, mostrar todos los roles
        if (in_array('administrador', $userRoles)) {
            $roles = Rol::orderBy('nombre')->get();
        } else {
            // Si es moderador, mostrar solo roles que no sean admin ni moderador
            $roles = Rol::whereNotIn('nombre', ['administrador', 'moderador'])
                       ->orderBy('nombre')
                       ->get();
        }

        return view('usuarios.form', compact('roles'));
    }

    // Guardar nuevo usuario
    public function store(Request $request)
    {
        // Verificar permisos de rol
        $userRoles = session('usuario_rol', []);
        if (is_string($userRoles)) {
            $userRoles = [$userRoles];
        }

        // Obtener el rol seleccionado
        $rolSeleccionado = Rol::find($request->rol_id);
        if (!$rolSeleccionado) {
            return redirect()->back()->withErrors(['El rol seleccionado no es válido.']);
        }

        // Si no es administrador y trata de asignar rol de admin o moderador
        if (!in_array('administrador', $userRoles) && 
            in_array($rolSeleccionado->nombre, ['administrador', 'moderador'])) {
            return redirect()->back()->withErrors(['No tienes permisos para asignar ese rol.']);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|min:8',
            'rol_id' => 'required|exists:roles,id',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $usuario = new Usuarios();
        $usuario->nombre = $validated['nombre'];
        $usuario->correo = $validated['correo'];
        $usuario->estado = $validated['estado'] ?? 'activo'; // Por defecto activo
        $usuario->contrasena = bcrypt($validated['password']);
        $usuario->save();

        $usuario->roles()->sync([$validated['rol_id']]);

        // 📧 Enviar correo de bienvenida al usuario creado por admin/moderador
        try {
            Mail::to($usuario->correo)->send(new \App\Mail\BienvenidaMeditech($usuario));
            Log::info('Correo de bienvenida enviado a usuario creado por admin/moderador', [
                'usuario_id' => $usuario->id,
                'correo' => $usuario->correo,
                'creado_por' => Auth::user()->nombre ?? 'Sistema'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al enviar correo de bienvenida (creación por admin)', [
                'usuario_id' => $usuario->id,
                'correo' => $usuario->correo,
                'error' => $e->getMessage()
            ]);
            // No fallar la creación si hay error con el correo
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente. Se ha enviado un correo de bienvenida.');
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

        // 🔍 Detectar cambios antes de actualizar
        $cambios = [];
        $valoresOriginales = [
            'nombre' => $usuario->nombre,
            'correo' => $usuario->correo,
            'estado' => $usuario->estado,
            'rol' => $usuario->roles->first()->nombre ?? 'Sin rol'
        ];

        // Detectar cambios en cada campo
        if ($usuario->nombre !== $validated['nombre']) {
            $cambios['nombre'] = [
                'anterior' => $usuario->nombre,
                'nuevo' => $validated['nombre']
            ];
        }

        if ($usuario->correo !== $validated['correo']) {
            $cambios['correo'] = [
                'anterior' => $usuario->correo,
                'nuevo' => $validated['correo']
            ];
        }

        if ($usuario->estado !== $validated['estado']) {
            $cambios['estado'] = [
                'anterior' => ucfirst($usuario->estado),
                'nuevo' => ucfirst($validated['estado'])
            ];
        }

        // Verificar cambio de rol
        $rolNuevo = Rol::find($validated['rol_id']);
        $rolActualUsuario = $usuario->roles->first();
        if (!$rolActualUsuario || $rolActualUsuario->id !== $validated['rol_id']) {
            $cambios['rol'] = [
                'anterior' => $rolActualUsuario->nombre ?? 'Sin rol',
                'nuevo' => $rolNuevo->nombre
            ];
        }

        // Guardar el correo original para el envío (en caso de que cambie)
        $correoOriginal = $usuario->correo;

        // Actualizar usuario
        $usuario->nombre = $validated['nombre'];
        $usuario->correo = $validated['correo'];
        $usuario->estado = $validated['estado'];
        $usuario->save();

        $usuario->roles()->sync([$validated['rol_id']]);

        // 📧 Enviar correo de notificación si hubo cambios
        if (!empty($cambios)) {
            try {
                $actualizadoPor = Auth::user()->nombre ?? 'Administrador del sistema';
                
                // Enviar al correo original si cambió, sino al actual
                $correoDestino = isset($cambios['correo']) ? $correoOriginal : $usuario->correo;
                
                Mail::to($correoDestino)->send(new \App\Mail\UsuarioActualizado($usuario, $cambios, $actualizadoPor));
                
                // Si cambió el correo, también enviar al nuevo
                if (isset($cambios['correo'])) {
                    Mail::to($usuario->correo)->send(new \App\Mail\UsuarioActualizado($usuario, $cambios, $actualizadoPor));
                }
                
                Log::info('Correo de actualización enviado', [
                    'usuario_id' => $usuario->id,
                    'cambios' => array_keys($cambios),
                    'actualizado_por' => $actualizadoPor,
                    'correo_destino' => $correoDestino
                ]);
            } catch (\Exception $e) {
                Log::error('Error al enviar correo de actualización', [
                    'usuario_id' => $usuario->id,
                    'cambios' => $cambios,
                    'error' => $e->getMessage()
                ]);
                // No fallar la actualización si hay error con el correo
            }
        }

        $mensaje = 'Usuario actualizado exitosamente.';
        if (!empty($cambios)) {
            $mensaje .= ' Se ha enviado una notificación de los cambios al usuario.';
        }

        return redirect()->route('usuarios.show', $usuario->id)->with('success', $mensaje);
    }

}
