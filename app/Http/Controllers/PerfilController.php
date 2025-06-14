<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Usuarios;
use App\Models\Medico;
use App\Models\Cita;

class PerfilController extends Controller
{    /**
     * Mostrar el perfil del usuario autenticado
     */
    public function show()
    {
        $usuario = Auth::user();
          // Cargar las relaciones necesarias
        $usuario = Usuarios::with('roles')->find($usuario->id);
        
        // Obtener información adicional según el tipo de usuario
        $datosAdicionales = [];
        
        // Si es médico, obtener información médica
        if ($usuario->roles->contains('nombre', 'Medico')) {
            $medico = Medico::with(['especialidades', 'clinicas'])->where('id', $usuario->id)->first();
            if ($medico) {
                $datosAdicionales['medico'] = $medico;
                $datosAdicionales['especialidades'] = $medico->especialidades;
                $datosAdicionales['clinicas'] = $medico->clinicas;
            }
        }
          // Si es paciente, obtener estadísticas de citas
        if ($usuario->roles->contains('nombre', 'Paciente')) {
            $datosAdicionales['estadisticas_citas'] = [
                'total' => \App\Models\Cita::where('paciente_id', $usuario->id)->count(),
                'aprobadas' => \App\Models\Cita::where('paciente_id', $usuario->id)->where('estado', 'aprobada')->count(),
                'pendientes' => \App\Models\Cita::where('paciente_id', $usuario->id)->where('estado', 'pendiente')->count(),
                'canceladas' => \App\Models\Cita::where('paciente_id', $usuario->id)->where('estado', 'cancelada')->count(),
            ];
        }
        
        return view('perfil.show', compact('usuario', 'datosAdicionales'));
    }
      /**
     * Mostrar el formulario de edición del perfil
     */    public function edit()
    {
        $usuario = Auth::user();
        $usuario = Usuarios::with('roles')->find($usuario->id);
        
        return view('perfil.edit', compact('usuario'));
    }
    
    /**
     * Actualizar el perfil del usuario
     */
    public function update(Request $request)
    {
        $usuario = Auth::user();
        
        // Validar los datos básicos
        $request->validate([
            'nombre' => 'required|string|max:150',
            'correo' => 'required|email|max:150|unique:usuarios,correo,' . $usuario->id,
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 150 caracteres',
            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'El correo electrónico debe tener un formato válido',
            'correo.unique' => 'Este correo electrónico ya está en uso',
            'correo.max' => 'El correo no puede tener más de 150 caracteres',
        ]);
        
        try {            // Actualizar datos básicos del usuario
            $usuario = Usuarios::find($usuario->id);
            $usuario->nombre = $request->nombre;
            $usuario->correo = $request->correo;
            $usuario->save();
            
            Log::info('Perfil actualizado', [
                'usuario_id' => $usuario->id,
                'nombre' => $request->nombre,
                'correo' => $request->correo,
            ]);
            
            return redirect()->route('perfil.show')
                ->with('success', 'Perfil actualizado exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al actualizar perfil', [
                'usuario_id' => $usuario->id,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el perfil. Intente nuevamente.');
        }
    }
    
    /**
     * Mostrar el formulario para cambiar contraseña
     */
    public function showChangePasswordForm()
    {
        return view('perfil.change-password');
    }
    
    /**
     * Cambiar la contraseña del usuario
     */
    public function changePassword(Request $request)
    {
        $usuario = Auth::user();
        
        // Validar los datos
        $request->validate([
            'contrasena_actual' => 'required',
            'contrasena_nueva' => 'required|min:8|confirmed',
        ], [
            'contrasena_actual.required' => 'La contraseña actual es obligatoria',
            'contrasena_nueva.required' => 'La nueva contraseña es obligatoria',
            'contrasena_nueva.min' => 'La nueva contraseña debe tener al menos 8 caracteres',
            'contrasena_nueva.confirmed' => 'La confirmación de la nueva contraseña no coincide',
        ]);
        
        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($request->contrasena_actual, $usuario->contrasena)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'La contraseña actual es incorrecta');
        }
        
        try {            // Actualizar la contraseña
            $usuario = Usuarios::find($usuario->id);
            $usuario->contrasena = Hash::make($request->contrasena_nueva);
            $usuario->save();
            
            Log::info('Contraseña cambiada', [
                'usuario_id' => $usuario->id,
            ]);
              return redirect()->route('perfil.show')
                ->with('success', '¡Contraseña actualizada exitosamente! Tu cuenta ahora está más segura.');
                
        } catch (\Exception $e) {
            Log::error('Error al cambiar contraseña', [
                'usuario_id' => $usuario->id,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->back()
                ->with('error', 'Error al cambiar la contraseña. Intente nuevamente.');
        }
    }
}
