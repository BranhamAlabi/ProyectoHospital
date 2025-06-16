<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clinica;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ClinicaController extends Controller
{
    public function __construct()
    {
        // No necesitamos middleware aquí, lo manejamos en las rutas
    }

    public function index(Request $request)
    {
        $query = Clinica::query();

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $clinicas = $query->orderBy('nombre')->get();
        $rolActual = session('usuario_rol');

        return view('clinica.index', compact('clinicas', 'rolActual'));
    }

    public function create()
    {
        return view('clinica.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'correo' => 'nullable|email|max:255',
            'responsable' => 'nullable|string|max:255',
            'estado' => 'required|in:activo,inactivo'
        ]);

        try {
            $clinica = Clinica::create($validated);
            
            // 📧 Enviar correo de notificación de clínica creada
            try {
                $creadaPor = Auth::user()->nombre ?? 'Administrador del sistema';
                
                // Enviar al correo de la clínica si tiene uno
                if ($clinica->correo) {
                    Mail::to($clinica->correo)->send(new \App\Mail\ClinicaCreada($clinica, $creadaPor));
                }
                
                // También enviar a administradores (opcional - aquí puedes agregar lógica para obtener emails de admins)
                
                Log::info('Correo de clínica creada enviado', [
                    'clinica_id' => $clinica->id,
                    'clinica_nombre' => $clinica->nombre,
                    'creada_por' => $creadaPor
                ]);
            } catch (\Exception $e) {
                Log::error('Error al enviar correo de clínica creada', [
                    'clinica_id' => $clinica->id,
                    'error' => $e->getMessage()
                ]);
                // No fallar la creación si hay error con el correo
            }
            
            return redirect()->route('clinica.index')->with('success', 'Clínica creada correctamente. Se ha enviado notificación por correo.');
        } catch (\Exception $e) {
            return redirect()->route('clinica.index')->withErrors('Error al crear la clínica.');
        }
    }

    public function show($id)
    {
        $clinica = Clinica::findOrFail($id);
        $rolActual = session('usuario_rol');
        return view('clinica.show', compact('clinica', 'rolActual'));
    }

    public function edit($id)
    {
        $clinica = Clinica::findOrFail($id);
        return view('clinica.edit', compact('clinica'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'correo' => 'nullable|email|max:255',
            'responsable' => 'nullable|string|max:255',
            'estado' => 'required|in:activo,inactivo'
        ]);

        $clinica = Clinica::findOrFail($id);
        
        // 🔍 Detectar cambios antes de actualizar
        $cambios = [];
        
        if ($clinica->nombre !== $validated['nombre']) {
            $cambios['nombre'] = [
                'anterior' => $clinica->nombre,
                'nuevo' => $validated['nombre']
            ];
        }
        
        if ($clinica->direccion !== $validated['direccion']) {
            $cambios['direccion'] = [
                'anterior' => $clinica->direccion ?? 'No especificada',
                'nuevo' => $validated['direccion'] ?? 'No especificada'
            ];
        }
        
        if ($clinica->telefono !== $validated['telefono']) {
            $cambios['telefono'] = [
                'anterior' => $clinica->telefono ?? 'No especificado',
                'nuevo' => $validated['telefono'] ?? 'No especificado'
            ];
        }
        
        if ($clinica->correo !== $validated['correo']) {
            $cambios['correo'] = [
                'anterior' => $clinica->correo ?? 'No especificado',
                'nuevo' => $validated['correo'] ?? 'No especificado'
            ];
        }
        
        if ($clinica->responsable !== $validated['responsable']) {
            $cambios['responsable'] = [
                'anterior' => $clinica->responsable ?? 'No especificado',
                'nuevo' => $validated['responsable'] ?? 'No especificado'
            ];
        }
        
        if ($clinica->estado !== $validated['estado']) {
            $cambios['estado'] = [
                'anterior' => ucfirst($clinica->estado),
                'nuevo' => ucfirst($validated['estado'])
            ];
        }
        
        // Guardar correo original para envío
        $correoOriginal = $clinica->correo;
        
        try {
            $clinica->update($validated);
            
            // 📧 Enviar correo de notificación si hubo cambios
            if (!empty($cambios)) {
                try {
                    $actualizadaPor = Auth::user()->nombre ?? 'Administrador del sistema';
                    
                    // Enviar al correo original si cambió, sino al actual
                    $correoDestino = isset($cambios['correo']) ? $correoOriginal : $clinica->correo;
                    
                    if ($correoDestino) {
                        Mail::to($correoDestino)->send(new \App\Mail\ClinicaActualizada($clinica, $cambios, $actualizadaPor));
                    }
                    
                    // Si cambió el correo, también enviar al nuevo
                    if (isset($cambios['correo']) && $clinica->correo) {
                        Mail::to($clinica->correo)->send(new \App\Mail\ClinicaActualizada($clinica, $cambios, $actualizadaPor));
                    }
                    
                    Log::info('Correo de clínica actualizada enviado', [
                        'clinica_id' => $clinica->id,
                        'cambios' => array_keys($cambios),
                        'actualizada_por' => $actualizadaPor
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error al enviar correo de clínica actualizada', [
                        'clinica_id' => $clinica->id,
                        'error' => $e->getMessage()
                    ]);
                    // No fallar la actualización si hay error con el correo
                }
            }
            
            $mensaje = 'Información de la clínica actualizada correctamente.';
            if (!empty($cambios)) {
                $mensaje .= ' Se ha enviado notificación de los cambios por correo.';
            }
            
            return redirect()->route('clinica.index')->with('success', $mensaje);
        } catch (\Exception $e) {
            return redirect()->route('clinica.index')->withErrors('Error al actualizar la información de la clínica.');
        }
    }

    public function destroy($id)
    {
        try {
            $clinica = Clinica::findOrFail($id);
            $clinica->delete();
            return redirect()->route('clinica.index')->with('success', 'Clínica eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('clinica.index')->withErrors('Error al eliminar la clínica.');
        }
    }
}
