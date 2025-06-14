<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clinica;

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
            Clinica::create($validated);
            return redirect()->route('clinica.index')->with('success', 'Clínica creada correctamente.');
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
        
        try {
            $clinica->update($validated);
            return redirect()->route('clinica.index')->with('success', 'Información de la clínica actualizada correctamente.');
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
