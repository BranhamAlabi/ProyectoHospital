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
    }

    /**
     * Obtener horarios disponibles de un médico (AJAX)
     */
    public function getHorariosDisponibles(Request $request)
    {
        $medicoId = $request->medico_id;
        $fecha = $request->fecha;
        $clinicaId = $request->clinica_id;

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
                $citasExistentes = Cita::where('medico_id', $medicoId)
                    ->where('fecha', $fecha)
                    ->where('hora', $horaActual)
                    ->where('clinica_id', $clinicaId)
                    ->whereIn('estado', ['pendiente', 'aprobada'])
                    ->count();

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
            'created_by' => $pacienteId
        ]);

        $mensaje = 'Cita agendada exitosamente con estado: ' . ucfirst($estadoAutomatico);
        
        if ($estadoAutomatico === 'cancelada') {
            $mensaje .= '. Su porcentaje de asistencia es muy bajo. Contacte al personal administrativo.';
        } elseif ($estadoAutomatico === 'pendiente') {
            $mensaje .= '. Su cita está pendiente de aprobación.';
        }

        return redirect()->route('paciente.inicio')->with('success', $mensaje);
    }

    /**
     * Cancelar una cita
     */
    public function cancelar(Request $request, $id)
    {
        $cita = Cita::where('id', $id)
            ->where('paciente_id', Auth::id())
            ->where('fecha', '>=', now()->toDateString())
            ->first();

        if (!$cita) {
            return response()->json(['error' => 'Cita no encontrada o no se puede cancelar.'], 404);
        }

        $cita->update(['estado' => 'cancelada']);

        return response()->json(['success' => 'Cita cancelada exitosamente.']);
    }

    /**
     * Cancelar una cita del paciente
     */
    public function cancelar($id)
    {
        try {
            $cita = Cita::where('id', $id)
                ->where('paciente_id', Auth::id())
                ->whereIn('estado', ['pendiente', 'aprobada'])
                ->firstOrFail();

            // Verificar que la cita sea futura
            if (Carbon::parse($cita->fecha)->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede cancelar una cita pasada.'
                ], 400);
            }

            $cita->update([
                'estado' => 'cancelada',
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cita cancelada exitosamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la cita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar todas las citas del paciente con filtros
     */
    public function index(Request $request)
    {
        $pacienteId = Auth::id();
        
        $query = Cita::where('paciente_id', $pacienteId)
            ->with(['medico.usuario', 'medico.especialidades', 'clinica']);

        // Aplicar filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
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
        $medicos = Medico::with('usuario')->get();

        // Estadísticas rápidas
        $totalCitas = Cita::where('paciente_id', $pacienteId)->count();
        $citasAprobadas = Cita::where('paciente_id', $pacienteId)->where('estado', 'aprobada')->count();
        $citasPendientes = Cita::where('paciente_id', $pacienteId)->where('estado', 'pendiente')->count();
        $citasCanceladas = Cita::where('paciente_id', $pacienteId)->where('estado', 'cancelada')->count();

        return view('paciente.citas', compact(
            'citas', 
            'especialidades', 
            'clinicas', 
            'medicos',
            'totalCitas',
            'citasAprobadas',
            'citasPendientes',
            'citasCanceladas'
        ));
    }
}
