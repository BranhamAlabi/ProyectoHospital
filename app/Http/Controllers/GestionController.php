<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clinica;
use App\Models\PacienteExpediente;

class GestionController extends Controller
{
    public function inicio()
    {
        return view('gestion.inicio');
    }    public function inicioPaciente()
    {
        $usuario = auth()->user();        // Obtener citas próximas y pasadas con relaciones
        $citasProximas = \App\Models\Cita::where('paciente_id', $usuario->id)
            ->where('fecha', '>=', now()->toDateString())
            ->with(['medico.usuario', 'medico.especialidades', 'clinica'])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        $citasPasadas = \App\Models\Cita::where('paciente_id', $usuario->id)
            ->where('fecha', '<', now()->toDateString())
            ->with(['medico.usuario', 'medico.especialidades', 'clinica'])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->get();

        // Calcular estadísticas
        $citasAprobadas = \App\Models\Cita::where('paciente_id', $usuario->id)
            ->where('estado', 'aprobada')
            ->count();

        $citasPendientes = \App\Models\Cita::where('paciente_id', $usuario->id)
            ->where('estado', 'pendiente')
            ->count();

        $citasCanceladas = \App\Models\Cita::where('paciente_id', $usuario->id)
            ->where('estado', 'cancelada')
            ->count();

        // Calcular porcentaje de asistencia
        $porcentajeAsistencia = \App\Models\Cita::calcularPorcentajeAsistencia($usuario->id);

        // Obtener notificaciones no leídas
        $notificaciones = collect(); // Temporal hasta implementar notificaciones
        try {
            $notificaciones = \App\Models\Notification::where('usuario_id', $usuario->id)
                ->noLeidas()
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            // Si no existe la tabla de notificaciones aún
        }

        // Obtener datos del perfil
        $perfil = $usuario;

        // Obtener expediente médico (simplificado)
        $expediente = collect(); // Temporal hasta implementar expediente
        try {
            $expediente = \App\Models\MedicalNote::where('paciente_id', $usuario->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            // Si no existe la tabla de expediente aún
        }

        return view('gestion.inicio_paciente', compact(
            'citasProximas', 
            'citasPasadas', 
            'citasAprobadas',
            'citasPendientes', 
            'citasCanceladas',
            'porcentajeAsistencia',
            'notificaciones', 
            'perfil', 
            'expediente'
        ));
    }

    public function expedientePaciente()
    {
        $usuario = auth()->user();
        $expediente = \App\Models\MedicalNote::where('paciente_id', $usuario->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('paciente.expediente', compact('expediente'));
    }

    public function expedientePacientePdf()
    {
        $usuario = auth()->user();
        $expediente = \App\Models\MedicalNote::where('paciente_id', $usuario->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Aquí se puede implementar la generación de PDF con una librería como Dompdf o Snappy
        // Por ahora, devolveremos un mensaje temporal
        return response()->json(['message' => 'Generación de PDF pendiente de implementación']);
    }

    public function notificacionesPaciente()
    {
        $usuario = auth()->user();
        $notificaciones = \App\Models\Notification::where('usuario_id', $usuario->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('paciente.notificaciones', compact('notificaciones'));
    }

    public function subirDocumentoPaciente(Request $request)
    {
        $request->validate([
            'documento' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $usuario = auth()->user();

        $path = $request->file('documento')->store('documentos_paciente');

        // Aquí se puede guardar la ruta del documento en la base de datos si es necesario

        return redirect()->back()->with('success', 'Documento subido correctamente.');
    }

    public function actualizarPerfilPaciente(Request $request)
    {
        $usuario = auth()->user();

        $request->validate([
            'nombre' => 'required|string|max:150',
            'correo' => 'required|email|unique:usuarios,correo,' . $usuario->id,
            'telefono' => 'nullable|string|max:20',
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->correo = $request->correo;
        $usuario->telefono = $request->telefono;
        $usuario->save();

        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function cambiarContrasenaPaciente(Request $request)
    {
        $usuario = auth()->user();

        $request->validate([
            'password_actual' => 'required',
            'password_nueva' => 'required|min:8|confirmed',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->password_actual, $usuario->contrasena)) {
            return redirect()->back()->withErrors(['password_actual' => 'La contraseña actual es incorrecta.']);
        }

        $usuario->contrasena = \Illuminate\Support\Facades\Hash::make($request->password_nueva);
        $usuario->save();

        return redirect()->back()->with('success', 'Contraseña cambiada correctamente.');
    }

    // New method to list clinics with filters
    public function listarClinicas(Request $request)
    {
        abort(404);
    }

    // Mostrar todos los expedientes médicos del paciente
    public function expedientesPaciente()
    {
        $usuario = auth()->user();
        
        // Obtener todas las citas del paciente con sus relaciones
        $expedientes = \App\Models\Cita::where('paciente_id', $usuario->id)
            ->where('estado', 'aprobada') // Solo citas aprobadas tienen expedientes médicos
            ->with([
                'medico.usuario', 
                'medico.especialidades', 
                'clinica',
                'medicalNotes' // Relación con las notas médicas
            ])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->get();
        
        // Calcular estadísticas
        $totalConsultas = $expedientes->count();
        
        $especialidadesVisitadas = $expedientes->pluck('medico.especialidades')
            ->flatten()
            ->pluck('especialidad')
            ->unique()
            ->count();
        
        $medicosVisitados = $expedientes->pluck('medico.id')
            ->unique()
            ->count();
        
        return view('paciente.expedientes', compact(
            'expedientes',
            'totalConsultas',
            'especialidadesVisitadas', 
            'medicosVisitados'
        ));
    }

    public function expedientePersonal()
    {
        $usuario = auth()->user();
        
        // Obtener el expediente personal del paciente
        $expedientePersonal = PacienteExpediente::where('id_paciente', $usuario->id)
            ->with('medico.usuario')
            ->first();

        return view('paciente.expediente_personal', compact('expedientePersonal'));
    }
}
