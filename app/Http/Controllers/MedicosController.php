<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Clinica;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MedicosController extends Controller
{
    // Listado de médicos activos con filtros
    public function index(Request $request)
    {
        $query = Medico::with(['usuario', 'clinicas', 'especialidades'])->whereHas('usuario', function ($q) {
            $q->where('estado', 'activo');
        });

        // Filtrar por clínica
        if ($request->filled('clinica')) {
            $query->whereHas('clinicas', function ($q) use ($request) {
                $q->where('id', $request->clinica);
            });
        }

        // Filtrar por nombre del médico
        if ($request->filled('nombre')) {
            $nombre = $request->nombre;
            $query->whereHas('usuario', function ($q) use ($nombre) {
                $q->where('nombre', 'like', "%{$nombre}%");
            });
        }

        // Filtrar por especialidad
        if ($request->filled('especialidad')) {
            $query->whereHas('especialidades', function ($q) use ($request) {
                $q->where('especialidad', 'like', '%' . $request->especialidad . '%');
            });
        }

        $medicos = $query->paginate(10);

        $clinicas = Clinica::all();

        return view('medicos.index', compact('medicos', 'clinicas'));
    }

    // Mostrar formulario para crear médico
    public function create()
    {
        $clinicas = Clinica::all();

        // Obtener usuarios con rol de medico
        $usuariosMedicos = Usuarios::whereHas('roles', function ($q) {
            $q->where('nombre', 'medico');
        })->get();

        $especialidades = \App\Models\Especialidad::all();

        return view('medicos.create', compact('clinicas', 'usuariosMedicos', 'especialidades'));
    }

    // Guardar nuevo médico
    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'clinica_ids' => 'required|array',
            'clinica_ids.*' => 'exists:clinicas,id',
            'especialidad_ids' => 'required|array',
            'especialidad_ids.*' => 'exists:especialidad,id',
        ]);

        // Verificar que el usuario no esté ya asignado como médico
        $existe = Medico::where('id', $validated['usuario_id'])->exists();
        if ($existe) {
            return redirect()->back()->withErrors('El usuario ya está asignado como médico.');
        }

        $medico = new Medico();
        $medico->id = $validated['usuario_id'];
        $medico->save();

        // Asociar clínicas
        $medico->clinicas()->sync($validated['clinica_ids']);

        // Asociar especialidades
        $medico->especialidades()->sync($validated['especialidad_ids']);

        // Enviar correo de notificación al médico
        $usuario = Usuarios::find($validated['usuario_id']);
        Mail::to($usuario->correo)->send(new \App\Mail\MedicoUpdated($medico, $usuario));

        return redirect()->route('medicos.index')->with('success', 'Médico agregado correctamente.');
    }

    // Mostrar formulario para editar médico
    public function edit($id)
    {
        $medico = Medico::with('usuario', 'clinicas', 'especialidades')->findOrFail($id);

        $rolActual = session('usuario_rol');

        $clinicas = Clinica::all();
        $especialidades = \App\Models\Especialidad::all();

        return view('medicos.edit', compact('medico', 'clinicas', 'especialidades', 'rolActual'));
    }

    // Actualizar médico
    public function update(Request $request, $id)
    {
        $medico = Medico::with('usuario')->findOrFail($id);

        $rolActual = session('usuario_rol');

        $rules = [
            'nombre' => 'required|string|max:150',
            'correo' => 'required|email|unique:usuarios,correo,' . $medico->usuario->id,
            'especialidad_ids' => 'required|array',
            'especialidad_ids.*' => 'exists:especialidad,id',
            'clinica_ids' => 'required|array',
            'clinica_ids.*' => 'exists:clinicas,id',
        ];

        $validated = $request->validate($rules);

        // Actualizar usuario
        $usuario = $medico->usuario;
        $usuario->nombre = $validated['nombre'];
        $usuario->correo = $validated['correo'];
        $usuario->save();

        // Actualizar médico
        $medico->save();

        // Actualizar clínicas asociadas
        $medico->clinicas()->sync($validated['clinica_ids']);

        // Actualizar especialidades asociadas
        $medico->especialidades()->sync($validated['especialidad_ids']);

        // Enviar notificación por correo al médico
        Mail::to($usuario->correo)->send(new \App\Mail\MedicoUpdated($medico, $usuario));

        return redirect()->route('medicos.index')->with('success', 'Médico actualizado correctamente.');
    }

    // Eliminar médico
    public function destroy($id)
    {
        $medico = Medico::findOrFail($id);

        // Confirmación previa debe ser manejada en la vista

        $usuario = $medico->usuario;

        $medico->clinicas()->detach();

        $medico->delete();

        // Enviar correo de notificación al médico
        Mail::to($usuario->correo)->send(new \App\Mail\MedicoUpdated($medico, $usuario));

        return redirect()->route('medicos.index')->with('success', 'Médico eliminado correctamente.');
    }

    // Mostrar inicio exclusivo para médicos
    public function inicio()
    {
        $usuario = auth()->user();
        $medico = Medico::where('id', $usuario->id)->with(['clinicas', 'especialidades'])->first();
        
        if (!$medico) {
            return redirect()->route('login')->with('error', 'No tienes permisos de médico.');
        }

        // Obtener citas del médico para la semana actual
        $fechaInicio = now()->startOfWeek();
        $fechaFin = now()->endOfWeek();
        
        $citas = \App\Models\Cita::where('medico_id', $medico->id)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->with(['paciente', 'clinica'])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        // Obtener notificaciones no leídas
        $notificaciones = \App\Models\Notification::where('usuario_id', $usuario->id)
            ->noLeidas()
            ->noSilenciadas()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Obtener horarios del médico
        $horarios = \App\Models\DoctorSchedule::where('medico_id', $medico->id)
            ->where('activo', true)
            ->get();

        return view('gestion.inicio_medico', compact('medico', 'citas', 'notificaciones', 'horarios'));
    }

    // Gestionar horarios del médico
    public function horarios()
    {
        $usuario = auth()->user();
        $medico = Medico::where('id', $usuario->id)->first();
        
        if (!$medico) {
            return redirect()->route('login')->with('error', 'No tienes permisos de médico.');
        }

        $horarios = \App\Models\DoctorSchedule::where('medico_id', $medico->id)->get();
        
        return view('medicos.horarios', compact('medico', 'horarios'));
    }

    // Guardar horarios del médico
    public function guardarHorarios(Request $request)
    {
        $usuario = auth()->user();
        $medico = Medico::where('id', $usuario->id)->first();
        
        if (!$medico) {
            return redirect()->route('login')->with('error', 'No tienes permisos de médico.');
        }

        $validated = $request->validate([
            'horarios' => 'required|array',
            'horarios.*.dia_semana' => 'required|string',
            'horarios.*.hora_inicio' => 'required|date_format:H:i',
            'horarios.*.hora_fin' => 'required|date_format:H:i|after:horarios.*.hora_inicio',
            'horarios.*.pacientes_por_hora' => 'required|integer|min:1|max:10',
        ]);

        // Eliminar horarios existentes
        \App\Models\DoctorSchedule::where('medico_id', $medico->id)->delete();

        // Crear nuevos horarios
        foreach ($validated['horarios'] as $horario) {
            \App\Models\DoctorSchedule::create([
                'medico_id' => $medico->id,
                'dia_semana' => $horario['dia_semana'],
                'hora_inicio' => $horario['hora_inicio'],
                'hora_fin' => $horario['hora_fin'],
                'pacientes_por_hora' => $horario['pacientes_por_hora'],
                'activo' => true
            ]);
        }

        return redirect()->route('medico.horarios')->with('success', 'Horarios actualizados correctamente.');
    }

    // Ver expedientes de pacientes
    public function expedientes()
    {
        $usuario = auth()->user();
        $medico = Medico::where('id', $usuario->id)->first();
        
        if (!$medico) {
            return redirect()->route('login')->with('error', 'No tienes permisos de médico.');
        }

        // Obtener pacientes que han tenido citas con este médico
        $pacientes = \App\Models\Usuarios::whereHas('citasPaciente', function($query) use ($medico) {
            $query->where('medico_id', $medico->id);
        })->with(['citasPaciente' => function($query) use ($medico) {
            $query->where('medico_id', $medico->id)->orderBy('fecha', 'desc');
        }])->get();

        return view('medicos.expedientes', compact('medico', 'pacientes'));
    }

    // Ver expediente específico de un paciente
    public function verExpediente($pacienteId)
    {
        $usuario = auth()->user();
        $medico = Medico::where('id', $usuario->id)->first();
        
        if (!$medico) {
            return redirect()->route('login')->with('error', 'No tienes permisos de médico.');
        }

        $paciente = \App\Models\Usuarios::findOrFail($pacienteId);
        
        // Obtener citas del paciente con este médico
        $citas = \App\Models\Cita::where('medico_id', $medico->id)
            ->where('paciente_id', $pacienteId)
            ->with(['notasMedicas'])
            ->orderBy('fecha', 'desc')
            ->get();

        return view('medicos.expediente_detalle', compact('medico', 'paciente', 'citas'));
    }

    // Actualizar estado de cita
    public function actualizarEstadoCita(Request $request, $citaId)
    {
        $usuario = auth()->user();
        $medico = Medico::where('id', $usuario->id)->first();
        
        if (!$medico) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $cita = \App\Models\Cita::where('id', $citaId)
            ->where('medico_id', $medico->id)
            ->first();

        if (!$cita) {
            return response()->json(['error' => 'Cita no encontrada'], 404);
        }

        $validated = $request->validate([
            'estado' => 'required|in:confirmada,cancelada,reprogramada,completada'
        ]);

        $cita->estado = $validated['estado'];
        $cita->save();

        // Crear notificación para el paciente
        \App\Models\Notification::create([
            'usuario_id' => $cita->paciente_id,
            'tipo' => 'cambio_estado',
            'titulo' => 'Estado de cita actualizado',
            'mensaje' => "Su cita del {$cita->fecha} ha sido {$validated['estado']}.",
        ]);

        return response()->json(['success' => true, 'message' => 'Estado actualizado correctamente']);
    }

    // Guardar notas médicas
    public function guardarNotasMedicas(Request $request, $citaId)
    {
        $usuario = auth()->user();
        $medico = Medico::where('id', $usuario->id)->first();
        
        if (!$medico) {
            return redirect()->back()->with('error', 'No autorizado');
        }

        $cita = \App\Models\Cita::where('id', $citaId)
            ->where('medico_id', $medico->id)
            ->first();

        if (!$cita) {
            return redirect()->back()->with('error', 'Cita no encontrada');
        }

        $validated = $request->validate([
            'diagnostico' => 'nullable|string',
            'tratamiento' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'notas_adicionales' => 'nullable|string',
        ]);

        \App\Models\MedicalNote::updateOrCreate(
            ['cita_id' => $citaId],
            [
                'medico_id' => $medico->id,
                'paciente_id' => $cita->paciente_id,
                'diagnostico' => $validated['diagnostico'],
                'tratamiento' => $validated['tratamiento'],
                'observaciones' => $validated['observaciones'],
                'notas_adicionales' => $validated['notas_adicionales'],
            ]
        );

        return redirect()->back()->with('success', 'Notas médicas guardadas correctamente');
    }
}
