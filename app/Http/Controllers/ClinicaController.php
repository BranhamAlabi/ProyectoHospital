<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clinica;

class ClinicaController extends Controller
{
    // Mostrar información de la clínica
    public function show()
    {
        $clinica = Clinica::first();

        $rolActual = session('usuario_rol');

        // Moderadores solo pueden ver (read-only)
        $readOnly = ($rolActual === 'moderador');

        return view('clinica.show', compact('clinica', 'readOnly'));
    }

    // Mostrar formulario para editar (solo admin)
    public function edit()
    {
        $rolActual = session('usuario_rol');

        if ($rolActual !== 'administrador') {
            return redirect()->route('clinica.show')->withErrors('No tienes permiso para editar la información de la clínica.');
        }

        $clinica = Clinica::first();

        return view('clinica.edit', compact('clinica'));
    }

    // Actualizar información de la clínica (solo admin)
    public function update(Request $request)
    {
        $rolActual = session('usuario_rol');

        if ($rolActual !== 'administrador') {
            return redirect()->route('clinica.show')->withErrors('No tienes permiso para actualizar la información de la clínica.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'responsable' => 'required|string|max:255',
        ]);

        $clinica = Clinica::first();

        if (!$clinica) {
            $clinica = new Clinica();
        }

        $clinica->nombre = $validated['nombre'];
        $clinica->direccion = $validated['direccion'];
        $clinica->telefono = $validated['telefono'];
        $clinica->email = $validated['email'];
        $clinica->responsable = $validated['responsable'];

        $clinica->save();

        return redirect()->route('clinica.show')->with('success', 'Información de la clínica actualizada correctamente.');
    }
}
