<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class CitaController extends Controller
{
    // Listado de citas con filtros y búsqueda
    public function index(Request $request)
    {
        $query = Cita::with('clinica');

        // Filtrado por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtrado por paciente
        if ($request->filled('paciente')) {
            $query->whereHas('paciente', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->paciente . '%');
            });
        }

        // Filtrado por médico
        if ($request->filled('medico')) {
            $query->whereHas('medico', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->medico . '%');
            });
        }

        // Filtrado por fecha
        if ($request->filled('fecha')) {
            $query->where('fecha', $request->fecha);
        }

        // Filtrado por clínica
        if ($request->filled('clinica')) {
            $query->where('clinica_id', $request->clinica);
        }

        // Obtener lista de clínicas para el filtro
        $clinicas = \App\Models\Clinica::all();

        // Ordenar por fecha y hora
        $citas = $query->orderBy('fecha', 'desc')->orderBy('hora', 'desc')->paginate(10);

        return view('citas.index', compact('citas', 'clinicas'));
    }

    // Mostrar detalle de cita
    public function show($id)
    {
        $cita = Cita::with(['paciente', 'medico', 'creador', 'actualizador'])->findOrFail($id);

        // Aquí se podría cargar el historial de cambios si se implementa

        return view('citas.show', compact('cita'));
    }

    // Actualizar estado de cita (aprobar, desaprobar, pendiente)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:aprobada,cancelada,pendiente,pendiente_reprogramacion',
            'comentarios' => 'nullable|string',
        ]);

        $cita = Cita::with(['paciente', 'medico', 'actualizador'])->findOrFail($id);

        // Verificar permisos y ámbito del moderador/admin aquí (pendiente implementar)

        $cita->estado = $request->estado;
        $cita->comentarios = $request->comentarios;
        $cita->updated_by = Auth::id();
        $cita->save();

        // Enviar correo de notificación a paciente y médico
        $updatedBy = $cita->actualizador;
        $emails = [];

        if ($cita->paciente && $cita->paciente->correo) {
            $emails[] = $cita->paciente->correo;
        }
        if ($cita->medico && $cita->medico->correo) {
            $emails[] = $cita->medico->correo;
        }

        if (!empty($emails)) {
            \Illuminate\Support\Facades\Mail::to($emails)->send(new \App\Mail\CitaUpdated($cita, $updatedBy));
        }

        // Registrar auditoría y enviar notificaciones (pendiente implementar)

        return redirect()->route('citas.show', $cita->id)->with('success', 'Estado de la cita actualizado correctamente.');
    }
}
