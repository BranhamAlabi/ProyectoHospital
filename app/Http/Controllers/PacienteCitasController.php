<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Usuarios;
use App\Models\Medico;
use App\Models\Especialidad;
use App\Models\DoctorSchedule;
use App\Models\Clinica;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PacienteCitasController extends Controller
{
    /**
     * Mostrar todas las citas del paciente con filtros
     */
    public function index(Request $request)
    {
        $pacienteId = Auth::id();
          $query = Cita::where('paciente_id', $pacienteId)
            ->with(['medico.usuario', 'medico.especialidades', 'clinica']);// Aplicar filtros
        if ($request->filled('estado')) {
            $query->whereRaw('LOWER(TRIM(estado)) = ?', [strtolower(trim($request->estado))]);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('medico')) {
            $query->whereHas('medico.usuario', function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->medico . '%');
            });
        }

        if ($request->filled('especialidad')) {
            $query->whereHas('medico.especialidades', function($q) use ($request) {
                $q->where('especialidad', 'like', '%' . $request->especialidad . '%');
            });
        }

        if ($request->filled('clinica')) {
            $query->whereHas('clinica', function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->clinica . '%');
            });
        }

        // Ordenar por fecha y hora (más recientes primero)
        $citas = $query->orderBy('fecha', 'desc')
                      ->orderBy('hora', 'desc')
                      ->paginate(10);

        // Obtener datos para los filtros
        $especialidades = Especialidad::all();
        $clinicas = Clinica::all();
        $medicos = Medico::with('usuario')->get();        // Estadísticas rápidas - usando case-insensitive comparisons
        $totalCitas = Cita::where('paciente_id', $pacienteId)->count();
        $citasAprobadas = Cita::where('paciente_id', $pacienteId)
            ->whereRaw('LOWER(TRIM(estado)) = ?', ['aprobada'])->count();
        $citasConfirmadas = Cita::where('paciente_id', $pacienteId)
            ->whereRaw('LOWER(TRIM(estado)) = ?', ['confirmada'])->count();
        $citasPendientes = Cita::where('paciente_id', $pacienteId)
            ->whereRaw('LOWER(TRIM(estado)) = ?', ['pendiente'])->count();
        $citasPendientesReprog = Cita::where('paciente_id', $pacienteId)
            ->whereRaw('LOWER(TRIM(estado)) = ?', ['pendiente_reprogramacion'])->count();
        $citasCanceladas = Cita::where('paciente_id', $pacienteId)
            ->whereRaw('LOWER(TRIM(estado)) = ?', ['cancelada'])->count();

        return view('paciente.citas', compact(
            'citas', 
            'especialidades', 
            'clinicas', 
            'medicos',
            'totalCitas',
            'citasAprobadas',
            'citasConfirmadas',
            'citasPendientes',
            'citasPendientesReprog',
            'citasCanceladas'
        ));
    }

    /**
     * Mostrar el formulario para crear una nueva cita
     */
    public function create()
    {
        $especialidades = Especialidad::all();
        $clinicas = Clinica::all();
        
        return view('paciente.crear_cita', compact('especialidades', 'clinicas'));
    }

    /**
     * Obtener médicos por especialidad (AJAX)
     */
    public function getMedicosPorEspecialidad(Request $request)
    {
        try {
            $especialidadId = $request->especialidad_id;
            $clinicaId = $request->clinica_id;

            if (!$especialidadId) {
                return response()->json(['error' => 'Especialidad requerida'], 400);
            }

            $query = Medico::with(['usuario', 'especialidades'])
                ->whereHas('especialidades', function($q) use ($especialidadId) {
                    $q->where('id', $especialidadId);
                });

            // Filtrar por clínica si está especificada
            if ($clinicaId) {
                $query->whereHas('clinicas', function($q) use ($clinicaId) {
                    $q->where('id', $clinicaId);
                });
            }

            $medicos = $query->get();

            // Transformar los datos para el frontend
            $medicosFormateados = $medicos->map(function($medico) {
                return [
                    'id' => $medico->id,
                    'usuario' => [
                        'nombre' => $medico->usuario->nombre ?? 'Nombre no disponible'
                    ],
                    'especialidades' => $medico->especialidades->map(function($esp) {
                        return ['especialidad' => $esp->especialidad];
                    })
                ];
            });

            return response()->json($medicosFormateados);

        } catch (\Exception $e) {
            Log::error('Error al cargar médicos: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }    /**
     * Obtener horarios disponibles de un médico (AJAX)
     */
    public function getHorariosDisponibles(Request $request)
    {
        $medicoId = $request->medico_id;
        $fecha = $request->fecha;
        $clinicaId = $request->clinica_id;
        $citaIdExcluir = $request->cita_id; // Para reprogramaciones

        $diaSemana = Carbon::parse($fecha)->locale('es')->dayName;
        $diasSemana = [
            'monday' => 'Lunes',
            'tuesday' => 'Martes', 
            'wednesday' => 'Miércoles',
            'thursday' => 'Jueves',
            'friday' => 'Viernes',
            'saturday' => 'Sábado',
            'sunday' => 'Domingo'
        ];
        $diaEnEspanol = $diasSemana[strtolower($diaSemana)] ?? $diaSemana;

        // Obtener horarios del médico para ese día y clínica
        $horarios = DoctorSchedule::where('medico_id', $medicoId)
            ->where('dia_semana', $diaEnEspanol)
            ->where('clinica_id', $clinicaId)
            ->get();

        $horariosDisponibles = [];

        foreach ($horarios as $horario) {
            $horaInicio = Carbon::parse($horario->hora_inicio);
            $horaFin = Carbon::parse($horario->hora_fin);
            
            while ($horaInicio < $horaFin) {
                $horaActual = $horaInicio->format('H:i');
                
                // Contar cuántas citas ya existen para esta hora
                $query = Cita::where('medico_id', $medicoId)
                    ->where('fecha', $fecha)
                    ->where('hora', $horaActual)
                    ->where('clinica_id', $clinicaId)
                    ->whereIn('estado', ['pendiente', 'aprobada']);
                
                // Excluir la cita actual si estamos reprogramando
                if ($citaIdExcluir) {
                    $query->where('id', '!=', $citaIdExcluir);
                }
                
                $citasExistentes = $query->count();

                // Si no ha alcanzado el máximo de pacientes, agregar al disponible
                if ($citasExistentes < $horario->pacientes_por_hora) {
                    $horariosDisponibles[] = [
                        'hora' => $horaActual,
                        'disponibles' => $horario->pacientes_por_hora - $citasExistentes,
                        'total' => $horario->pacientes_por_hora,
                        'schedule_id' => $horario->id
                    ];
                }
                
                $horaInicio->addHour();
            }
        }

        return response()->json($horariosDisponibles);
    }

    /**
     * Almacenar una nueva cita
     */
    public function store(Request $request)
    {
        $request->validate([
            'especialidad_id' => 'required|exists:especialidad,id',
            'medico_id' => 'required|exists:medicos,id',
            'clinica_id' => 'required|exists:clinicas,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'motivo' => 'required|string|max:500',
            'doctor_schedule_id' => 'required|exists:doctor_schedules,id'
        ]);

        $pacienteId = Auth::id();

        // Verificar que la hora esté disponible
        $citasExistentes = Cita::where('medico_id', $request->medico_id)
            ->where('fecha', $request->fecha)
            ->where('hora', $request->hora)
            ->where('clinica_id', $request->clinica_id)
            ->whereIn('estado', ['pendiente', 'aprobada'])
            ->count();

        $horario = DoctorSchedule::find($request->doctor_schedule_id);
        
        if ($citasExistentes >= $horario->pacientes_por_hora) {
            return back()->withErrors(['hora' => 'Esta hora ya no está disponible.']);
        }

        // Determinar estado automático basado en historial de asistencia
        $estadoAutomatico = Cita::determinarEstadoAutomatico($pacienteId);

        $cita = Cita::create([
            'paciente_id' => $pacienteId,
            'medico_id' => $request->medico_id,
            'clinica_id' => $request->clinica_id,
            'doctor_schedule_id' => $request->doctor_schedule_id,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'motivo' => $request->motivo,
            'estado' => $estadoAutomatico,
            'created_by' => $pacienteId,
        ]);

        return redirect()->route('gestion.inicioPaciente')
            ->with('success', "Cita agendada exitosamente. Estado: {$estadoAutomatico}");
    }

    /**
     * Cancelar una cita
     */    public function cancelar(Request $request, $id)
    {
        $cita = Cita::where('id', $id)
            ->where('paciente_id', Auth::id())
            ->where('fecha', '>=', now()->toDateString())
            ->first();

        if (!$cita) {
            return response()->json(['error' => 'Cita no encontrada o no se puede cancelar.'], 404);
        }

        // Actualizar estado a cancelada y marcar como inasistencia
        $cita->update([
            'estado' => 'cancelada',
            'asistio' => false // Marcar como inasistencia cuando el paciente cancela
        ]);

        return response()->json(['success' => 'Cita cancelada exitosamente.']);
    }

    /**
     * Mostrar formulario para reprogramar cita
     */
    public function reprogramar($id)
    {
        $cita = Cita::with(['medico.usuario', 'medico.especialidades', 'clinica'])
            ->where('id', $id)
            ->where('paciente_id', Auth::id())
            ->firstOrFail();

        // Verificar que la cita se puede reprogramar
        if (!in_array($cita->estado, ['aprobada', 'pendiente'])) {
            return redirect()->route('paciente.citas.index')
                ->with('error', 'Esta cita no se puede reprogramar porque está ' . $cita->estado);
        }

        // Verificar que la cita no sea del pasado
        if (Carbon::parse($cita->fecha)->isPast()) {
            return redirect()->route('paciente.citas.index')
                ->with('error', 'No se pueden reprogramar citas que ya pasaron');
        }

        return view('paciente.reprogramar_cita', compact('cita'));
    }

    /**
     * Actualizar cita reprogramada
     */
    public function reprogramarUpdate(Request $request, $id)
    {
        $cita = Cita::where('id', $id)
            ->where('paciente_id', Auth::id())
            ->firstOrFail();

        // Validar que la cita se puede reprogramar
        if (!in_array($cita->estado, ['aprobada', 'pendiente'])) {
            return redirect()->route('paciente.citas.index')
                ->with('error', 'Esta cita no se puede reprogramar');
        }        // Validación de datos
        $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|string',
            'doctor_schedule_id' => 'required|exists:doctor_schedules,id',
            'comentarios' => 'required|string|min:10|max:500',
            'motivo' => 'required|string|min:5|max:500'
        ], [
            'fecha.required' => 'La fecha es obligatoria',
            'fecha.after_or_equal' => 'La fecha debe ser hoy o posterior',
            'hora.required' => 'La hora es obligatoria',
            'comentarios.required' => 'Debe explicar la razón para reprogramar',
            'comentarios.min' => 'La razón debe tener al menos 10 caracteres',
            'doctor_schedule_id.required' => 'Debe seleccionar un horario válido',
            'motivo.required' => 'El motivo de consulta es obligatorio',
            'motivo.min' => 'El motivo debe tener al menos 5 caracteres'
        ]);

        try {
            // Verificar que hay cambios
            $fechaCambiada = $request->fecha != $cita->fecha;
            $horaCambiada = $request->hora != $cita->hora;
            
            if (!$fechaCambiada && !$horaCambiada) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Debe cambiar al menos la fecha o la hora');
            }

            // Verificar disponibilidad del nuevo horario
            $horarioOcupado = Cita::where('medico_id', $cita->medico_id)
                ->where('fecha', $request->fecha)
                ->where('hora', $request->hora)
                ->where('id', '!=', $cita->id)
                ->where('estado', '!=', 'cancelada')
                ->exists();

            if ($horarioOcupado) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'El horario seleccionado ya está ocupado');
            }            // Actualizar la cita
            $cita->update([
                'fecha' => $request->fecha,
                'hora' => $request->hora,
                'doctor_schedule_id' => $request->doctor_schedule_id,
                'comentarios' => $request->comentarios,
                'motivo' => $request->motivo,
                'estado' => 'pendiente', // Cambiar estado a pendiente
                'updated_by' => Auth::id()
            ]);

            Log::info('Cita reprogramada', [
                'cita_id' => $cita->id,
                'paciente_id' => Auth::id(),
                'fecha_anterior' => $cita->getOriginal('fecha'),
                'hora_anterior' => $cita->getOriginal('hora'),
                'fecha_nueva' => $request->fecha,
                'hora_nueva' => $request->hora,
                'razon' => $request->comentarios
            ]);

            return redirect()->route('paciente.citas.index')
                ->with('success', 'Cita reprogramada exitosamente. Su estado ha cambiado a "Pendiente" y será revisada por el personal médico.');

        } catch (\Exception $e) {
            Log::error('Error al reprogramar cita', [
                'cita_id' => $id,
                'error' => $e->getMessage(),
                'paciente_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al reprogramar la cita. Intente nuevamente.');
        }
    }
}
