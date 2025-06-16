<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Clinica;
use App\Models\Usuarios;
use App\Models\PacienteExpediente;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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

        // 📧 Enviar correo de notificación de asignación como médico
        try {
            $medico->load('usuario', 'clinicas', 'especialidades');
            $asignadoPor = Auth::user()->nombre ?? 'Administrador del sistema';
            
            Mail::to($medico->usuario->correo)->send(new \App\Mail\MedicoAsignado($medico, $asignadoPor));
            
            Log::info('Correo de médico asignado enviado', [
                'medico_id' => $medico->id,
                'usuario_correo' => $medico->usuario->correo,
                'asignado_por' => $asignadoPor
            ]);
        } catch (\Exception $e) {
            Log::error('Error al enviar correo de médico asignado', [
                'medico_id' => $medico->id,
                'error' => $e->getMessage()
            ]);
            // No fallar la asignación si hay error con el correo
        }

        return redirect()->route('medicos.index')->with('success', 'Médico agregado correctamente. Se ha enviado notificación por correo.');
    }

    // Mostrar formulario para editar médico
    public function edit($id)
    {
        $medico = Medico::with('usuario', 'clinicas', 'especialidades')->findOrFail($id);

        $rolActual = session('usuario_rol');

        $clinicas = Clinica::all();
        $especialidades = \App\Models\Especialidad::all();

        return view('medicos.edit', compact('medico', 'clinicas', 'especialidades', 'rolActual'));
    }    // Actualizar médico
    public function update(Request $request, $id)
    {
        $medico = Medico::with(['usuario', 'clinicas', 'especialidades'])->findOrFail($id);

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

        // 🔍 Detectar cambios antes de actualizar
        $cambios = [];
        $usuario = $medico->usuario;
        $correoOriginal = $usuario->correo;

        // Detectar cambios en datos básicos del usuario
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

        // Detectar cambios en especialidades
        $especialidadesActuales = $medico->especialidades->pluck('id')->sort()->values()->toArray();
        $especialidadesNuevas = collect($validated['especialidad_ids'])->sort()->values()->toArray();
        
        if ($especialidadesActuales != $especialidadesNuevas) {
            $especialidadesActualesNombres = $medico->especialidades->pluck('especialidad')->toArray();
            $especialidadesNuevasNombres = \App\Models\Especialidad::whereIn('id', $validated['especialidad_ids'])->pluck('especialidad')->toArray();
            
            $cambios['especialidades'] = [
                'anterior' => empty($especialidadesActualesNombres) ? 'Sin especialidades' : implode(', ', $especialidadesActualesNombres),
                'nuevo' => empty($especialidadesNuevasNombres) ? 'Sin especialidades' : implode(', ', $especialidadesNuevasNombres)
            ];
        }

        // Detectar cambios en clínicas
        $clinicasActuales = $medico->clinicas->pluck('id')->sort()->values()->toArray();
        $clinicasNuevas = collect($validated['clinica_ids'])->sort()->values()->toArray();
        
        if ($clinicasActuales != $clinicasNuevas) {
            $clinicasActualesNombres = $medico->clinicas->pluck('nombre')->toArray();
            $clinicasNuevasNombres = \App\Models\Clinica::whereIn('id', $validated['clinica_ids'])->pluck('nombre')->toArray();
            
            $cambios['clinicas'] = [
                'anterior' => empty($clinicasActualesNombres) ? 'Sin clínicas' : implode(', ', $clinicasActualesNombres),
                'nuevo' => empty($clinicasNuevasNombres) ? 'Sin clínicas' : implode(', ', $clinicasNuevasNombres)
            ];
        }

        try {
            // Actualizar usuario
            $usuario->nombre = $validated['nombre'];
            $usuario->correo = $validated['correo'];
            $usuario->save();

            // Actualizar médico
            $medico->save();

            // Actualizar clínicas asociadas
            $medico->clinicas()->sync($validated['clinica_ids']);

            // Actualizar especialidades asociadas
            $medico->especialidades()->sync($validated['especialidad_ids']);

            // 📧 Enviar correo de notificación si hubo cambios
            if (!empty($cambios)) {
                try {
                    // Recargar el médico con las nuevas relaciones
                    $medico->load('usuario', 'clinicas', 'especialidades');
                    $actualizadoPor = Auth::user()->nombre ?? 'Administrador del sistema';
                    
                    // Enviar al correo original si cambió el correo, sino al actual
                    $correoDestino = isset($cambios['correo']) ? $correoOriginal : $usuario->correo;
                    
                    Mail::to($correoDestino)->send(new \App\Mail\MedicoActualizado($medico, $cambios, $actualizadoPor));
                    
                    // Si cambió el correo, también enviar al nuevo
                    if (isset($cambios['correo']) && $usuario->correo !== $correoOriginal) {
                        Mail::to($usuario->correo)->send(new \App\Mail\MedicoActualizado($medico, $cambios, $actualizadoPor));
                    }
                    
                    Log::info('Correo de médico actualizado enviado', [
                        'medico_id' => $medico->id,
                        'cambios' => array_keys($cambios),
                        'actualizado_por' => $actualizadoPor
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error al enviar correo de médico actualizado', [
                        'medico_id' => $medico->id,
                        'error' => $e->getMessage()
                    ]);
                    // No fallar la actualización si hay error con el correo
                }
            }

            $mensaje = 'Médico actualizado correctamente.';
            if (!empty($cambios)) {
                $mensaje .= ' Se ha enviado notificación de los cambios por correo.';
            }

            return redirect()->route('medicos.index')->with('success', $mensaje);
        } catch (\Exception $e) {
            Log::error('Error al actualizar médico', [
                'medico_id' => $medico->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('medicos.index')->withErrors('Error al actualizar la información del médico.');
        }
    }    // Eliminar médico
    public function destroy($id)
    {
        $medico = Medico::with(['usuario', 'clinicas', 'especialidades'])->findOrFail($id);

        // Confirmación previa debe ser manejada en la vista

        $usuario = $medico->usuario;
        $correoMedico = $usuario->correo;
        $nombreMedico = $usuario->nombre;

        try {
            $medico->clinicas()->detach();
            $medico->especialidades()->detach();
            $medico->delete();

            // 📧 Enviar correo de notificación al médico sobre la eliminación
            try {
                $eliminadoPor = Auth::user()->nombre ?? 'Administrador del sistema';
                
                // Crear un correo personalizado para la eliminación
                $subject = 'Notificación: Asignación como médico removida - ' . config('app.name');
                $mensaje = "
                Estimado(a) Dr(a). {$nombreMedico},

                Te informamos que tu asignación como médico en nuestro sistema ha sido removida.

                Si tienes alguna duda sobre esta acción, por favor contacta al equipo de administración.

                Acción realizada por: {$eliminadoPor}
                Fecha: " . now()->format('d/m/Y H:i:s') . "

                Atentamente,
                Equipo de " . config('app.name');

                Mail::raw($mensaje, function ($mail) use ($correoMedico, $subject) {
                    $mail->to($correoMedico)->subject($subject);
                });
                
                Log::info('Correo de médico eliminado enviado', [
                    'medico_id' => $id,
                    'usuario_correo' => $correoMedico,
                    'eliminado_por' => $eliminadoPor
                ]);
            } catch (\Exception $e) {
                Log::error('Error al enviar correo de médico eliminado', [
                    'medico_id' => $id,
                    'error' => $e->getMessage()
                ]);
                // No fallar la eliminación si hay error con el correo
            }

            return redirect()->route('medicos.index')->with('success', 'Médico eliminado correctamente. Se ha enviado notificación por correo.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar médico', [
                'medico_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('medicos.index')->withErrors('Error al eliminar el médico.');
        }
    }// Mostrar inicio exclusivo para médicos
    public function inicio(Request $request)
    {
        $usuario = auth()->user();
        $medico = Medico::where('id', $usuario->id)->with(['clinicas', 'especialidades'])->first();
        
        if (!$medico) {
            return redirect()->route('login')->with('error', 'No tienes permisos de médico.');
        }

        // Configurar filtros de fecha
        $filtro = $request->get('filtro', 'semana'); // semana, proximas, pasadas
        $fechaCustom = $request->get('fecha');
        
        $query = \App\Models\Cita::where('medico_id', $medico->id)
            ->with(['paciente', 'clinica']);

        // Aplicar filtros de fecha
        switch ($filtro) {
            case 'proximas':
                $query->where('fecha', '>', now()->toDateString());
                break;
            case 'pasadas':
                $query->where('fecha', '<', now()->toDateString());
                break;
            case 'fecha_custom':
                if ($fechaCustom) {
                    $query->whereDate('fecha', $fechaCustom);
                }
                break;
            case 'semana':
            default:
                // Citas de la semana actual (por defecto)
                $fechaInicio = now()->startOfWeek();
                $fechaFin = now()->endOfWeek();
                $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
                break;
        }

        // Filtros adicionales
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        if ($request->has('paciente') && $request->paciente != '') {
            $query->whereHas('paciente', function($q) use ($request) {
                $q->where('nombre', 'LIKE', '%' . $request->paciente . '%');
            });
        }

        $citas = $query->orderBy('fecha')
            ->orderBy('hora')
            ->get();        // Obtener estadísticas generales
        $totalCitas = \App\Models\Cita::where('medico_id', $medico->id)->count();
        $citasAprobadas = \App\Models\Cita::where('medico_id', $medico->id)
            ->whereRaw('LOWER(estado) = ?', ['aprobada'])->count();
        $citasPendientes = \App\Models\Cita::where('medico_id', $medico->id)
            ->whereRaw('LOWER(estado) = ?', ['pendiente'])->count();
        $citasCanceladas = \App\Models\Cita::where('medico_id', $medico->id)
            ->whereRaw('LOWER(estado) = ?', ['cancelada'])->count();// Estadísticas de la semana actual
        $fechaInicioSemana = now()->startOfWeek();
        $fechaFinSemana = now()->endOfWeek();
        
        $citasSemanaSolo = \App\Models\Cita::where('medico_id', $medico->id)
            ->whereBetween('fecha', [$fechaInicioSemana, $fechaFinSemana])
            ->get();

        $citasAprobadasSemana = $citasSemanaSolo->filter(function($cita) {
            return strtolower($cita->estado) === 'aprobada';
        })->count();
        $citasPendientesSemana = $citasSemanaSolo->filter(function($cita) {
            return strtolower($cita->estado) === 'pendiente';
        })->count();
        $citasConfirmadasSemana = $citasSemanaSolo->filter(function($cita) {
            return strtolower($cita->estado) === 'confirmada';
        })->count();
        $citasPendientesReprogramacionSemana = $citasSemanaSolo->filter(function($cita) {
            return strtolower($cita->estado) === 'pendiente_reprogramacion';
        })->count();
        $citasCanceladasSemana = $citasSemanaSolo->filter(function($cita) {
            return strtolower($cita->estado) === 'cancelada';
        })->count();// Obtener notificaciones no leídas
        $notificaciones = collect(); // Temporal hasta implementar notificaciones
        try {
            $notificaciones = \App\Models\Notification::where('usuario_id', $usuario->id)
                ->noLeidas()
                ->noSilenciadas()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            // Si no existe la tabla de notificaciones aún
        }

        // Obtener horarios del médico
        $horarios = collect(); // Temporal hasta asegurar que no haya errores
        try {
            $horarios = \App\Models\DoctorSchedule::where('medico_id', $medico->id)
                ->get();
        } catch (\Exception $e) {
            // Si no existe la tabla aún
        }        return view('gestion.inicio_medico', compact(
            'medico', 
            'citas', 
            'totalCitas',
            'citasAprobadas',
            'citasPendientes',
            'citasCanceladas',
            'citasAprobadasSemana',
            'citasPendientesSemana',
            'citasConfirmadasSemana',
            'citasPendientesReprogramacionSemana',
            'citasCanceladasSemana',
            'notificaciones',
            'horarios',
            'filtro',
            'fechaCustom'
        ));
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
        
        // Obtener las clínicas donde trabaja el médico
        $clinicas = $medico->clinicas;
        
        return view('medicos.horarios', compact('medico', 'horarios', 'clinicas'));
    }// Guardar horarios del médico
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
            'horarios.*.clinica_id' => 'required|exists:clinicas,id',
            'horarios.*.hora_inicio' => [
                'required',
                'regex:/^([0-9]|0[0-9]|1[0-9]|2[0-3]):(00)$/' // Solo horas en punto
            ],
            'horarios.*.hora_fin' => [
                'required',
                'regex:/^([0-9]|0[0-9]|1[0-9]|2[0-3]):(00)$/', // Solo horas en punto
                'after:horarios.*.hora_inicio'
            ],
            'horarios.*.pacientes_por_hora' => 'required|integer|min:1|max:10',
        ], [
            'horarios.*.hora_inicio.regex' => 'La hora de inicio debe ser una hora en punto (ej: 08:00, 09:00)',
            'horarios.*.hora_fin.regex' => 'La hora de fin debe ser una hora en punto (ej: 08:00, 09:00)',
            'horarios.*.hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio',
            'horarios.*.clinica_id.required' => 'Debe seleccionar una clínica',
            'horarios.*.clinica_id.exists' => 'La clínica seleccionada no existe',
        ]);

        // Validar solapamientos
        $horarios = $validated['horarios'];
        $errores = [];

        for ($i = 0; $i < count($horarios); $i++) {
            for ($j = $i + 1; $j < count($horarios); $j++) {
                $h1 = $horarios[$i];
                $h2 = $horarios[$j];

                // Solo validar si es el mismo día y la misma clínica
                if ($h1['dia_semana'] === $h2['dia_semana'] && $h1['clinica_id'] === $h2['clinica_id']) {
                    $inicio1 = strtotime($h1['hora_inicio']);
                    $fin1 = strtotime($h1['hora_fin']);
                    $inicio2 = strtotime($h2['hora_inicio']);
                    $fin2 = strtotime($h2['hora_fin']);

                    // Verificar solapamiento
                    if ($inicio1 < $fin2 && $fin1 > $inicio2) {
                        $clinica = \App\Models\Clinica::find($h1['clinica_id']);
                        $errores[] = "Solapamiento detectado en {$h1['dia_semana']} de {$h1['hora_inicio']} a {$h1['hora_fin']} con {$h2['hora_inicio']} a {$h2['hora_fin']} en {$clinica->nombre}";
                    }
                }
            }
        }        if (!empty($errores)) {
            return redirect()->back()->withErrors($errores)->withInput();
        }

        // Obtener horarios existentes antes de eliminarlos
        $horariosExistentes = \App\Models\DoctorSchedule::where('medico_id', $medico->id)->get();        // Analizar cambios y identificar citas afectadas
        $citasAfectadas = $this->identificarCitasAfectadasPorCambioHorario($medico->id, $horariosExistentes, $validated['horarios']);
        
        // Cambiar estado de citas afectadas a pendiente_reprogramacion
        if (!empty($citasAfectadas)) {
            Cita::whereIn('id', $citasAfectadas)->update([
                'estado' => 'Pendiente_reprogramacion',
                'comentarios' => 'Cita marcada para reprogramación debido a cambio en horarios del médico',
                'updated_at' => now()
            ]);
        }

        // Eliminar horarios existentes
        \App\Models\DoctorSchedule::where('medico_id', $medico->id)->delete();

        // Crear nuevos horarios
        foreach ($validated['horarios'] as $horario) {
            \App\Models\DoctorSchedule::create([
                'medico_id' => $medico->id,
                'clinica_id' => $horario['clinica_id'],
                'dia_semana' => $horario['dia_semana'],
                'hora_inicio' => $horario['hora_inicio'],
                'hora_fin' => $horario['hora_fin'],
                'pacientes_por_hora' => $horario['pacientes_por_hora'],
                'activo' => true
            ]);
        }

        $mensaje = 'Horarios actualizados correctamente.';
        if (!empty($citasAfectadas)) {
            $cantidadCitas = count($citasAfectadas);
            $mensaje .= " Se marcaron {$cantidadCitas} cita(s) para reprogramación debido a los cambios en horarios.";
        }

        return redirect()->route('medico.horarios')->with('success', $mensaje);
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
    }    /**
     * Actualizar estado de cita
     */    
    public function actualizarEstadoCita(Request $request, $citaId)
    {
        $request->validate([
            'estado' => 'required|in:Pendiente,Confirmada,Cancelada,Pendiente_reprogramacion',
            'comentarios' => 'nullable|string'
        ]);

        $cita = \App\Models\Cita::findOrFail($citaId);
        
        // Verificar que la cita pertenece al médico actual
        $medicoId = Auth::id();
        if ($cita->medico_id != $medicoId) {
            return redirect()->back()->with('error', 'No tienes permiso para actualizar esta cita.');
        }

        // 🚫 NUEVA RESTRICCIÓN: No permitir editar citas ya confirmadas o canceladas
        if (in_array($cita->estado, ['Confirmada', 'Cancelada'])) {
            return redirect()->back()->with('error', 'No puedes modificar una cita que ya ha sido confirmada o cancelada.');
        }

        $estadoAnterior = $cita->estado;
        $cita->estado = $request->estado;
        $cita->comentarios = $request->comentarios;
        
        // 🚫 Lógica para registrar inasistencias
        if ($request->estado === 'Cancelada') {
            // Cuando el médico cancela una cita, se considera inasistencia del paciente
            $cita->asistio = false;
            \Log::info('Cita cancelada por médico - registrada como inasistencia', [
                'cita_id' => $citaId,
                'paciente_id' => $cita->paciente_id,
                'medico_id' => $medicoId,
                'comentarios' => $request->comentarios
            ]);
        } elseif ($request->estado === 'Confirmada') {
            // Cuando se confirma la cita, se considera que el paciente asistió
            $cita->asistio = true;
            \Log::info('Cita confirmada - registrada como asistencia', [
                'cita_id' => $citaId,
                'paciente_id' => $cita->paciente_id,
                'medico_id' => $medicoId
            ]);
        }
        // Para estados 'Pendiente' y 'Pendiente_reprogramacion' no se modifica asistio
        
        $cita->save();

        // Mostrar modal de expediente solo si se confirma la cita
        if ($estadoAnterior !== 'Confirmada' && $request->estado === 'Confirmada') {
            return redirect()->back()->with([
                'success' => 'Cita confirmada correctamente. El paciente ha sido registrado como asistente.',
                'mostrar_expediente' => true,
                'cita_id' => $citaId
            ]);
        }

        // Mensaje específico para cancelaciones
        if ($request->estado === 'Cancelada') {
            return redirect()->back()->with('warning', 'Cita cancelada. Se ha registrado como inasistencia del paciente.');
        }

        return redirect()->back()->with('success', 'Estado de la cita actualizado correctamente.');
    }public function guardarExpediente(Request $request)
    {
        $request->validate([
            'cita_id' => 'required|exists:citas,id',
            'paciente_id' => 'required|exists:usuarios,id',
            'medico_id' => 'required|exists:usuarios,id',
            'notas' => 'required|string'
        ]);

        // Verificar que el médico actual es el que está creando el expediente
        if ($request->medico_id != Auth::id()) {
            return redirect()->back()->withErrors(['error' => 'No tienes permiso para crear este expediente.']);
        }

        // Verificar que la cita existe y está confirmada
        $cita = \App\Models\Cita::findOrFail($request->cita_id);
        if ($cita->estado !== 'Confirmada') {
            return redirect()->back()->withErrors(['error' => 'Solo se pueden crear expedientes para citas confirmadas.']);
        }

        // Verificar que la cita pertenece al médico autenticado
        if ($cita->medico_id !== Auth::id()) {
            return redirect()->back()->withErrors(['error' => 'No tienes permiso para crear expedientes para esta cita.']);
        }

        // Verificar que no existe un expediente previo para esta cita
        $expedienteExistente = \App\Models\ExpedienteMedico::where('cita_id', $request->cita_id)->exists();
        if ($expedienteExistente) {
            return redirect()->back()->withErrors(['error' => 'Ya existe un expediente para esta cita.']);
        }

        $expediente = new \App\Models\ExpedienteMedico();
        $expediente->paciente_id = $request->paciente_id;        $expediente->medico_id = $request->medico_id;
        $expediente->cita_id = $request->cita_id;
        $expediente->notas = $request->notas;
        $expediente->save();

        return redirect()->back()->with('success', 'Expediente médico creado correctamente.');
    }

    // Método para obtener los expedientes de un paciente específico (AJAX)
    public function listarExpedientesPaciente($pacienteId)
    {
        // Verificar que el paciente existe
        $paciente = \App\Models\Usuarios::findOrFail($pacienteId);
        
        // Obtener todos los expedientes del paciente que fueron creados por el médico autenticado
        $expedientes = \App\Models\ExpedienteMedico::where('paciente_id', $pacienteId)
            ->where('medico_id', Auth::id())
            ->with(['cita'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Formatear los datos para la respuesta
        $expedientesData = $expedientes->map(function ($expediente) {
            return [
                'id' => $expediente->id,
                'notas' => $expediente->notas,
                'created_at' => $expediente->created_at->format('d/m/Y H:i'),
                'fecha_cita' => $expediente->cita ? \Carbon\Carbon::parse($expediente->cita->fecha)->format('d/m/Y') : 'N/A',
                'hora_cita' => $expediente->cita ? \Carbon\Carbon::parse($expediente->cita->hora)->format('H:i') : 'N/A',
                'motivo_cita' => $expediente->cita ? $expediente->cita->motivo : 'N/A',
            ];
        });

        return response()->json([
            'success' => true,
            'paciente' => [
                'id' => $paciente->id,
                'nombre' => $paciente->nombre,
                'correo' => $paciente->correo
            ],
            'expedientes' => $expedientesData
        ]);
    }
    
    // Mostrar formulario de expediente personal del paciente
    public function expedientePersonal($pacienteId)
    {
        $usuario = auth()->user();
        $medico = Medico::where('id', $usuario->id)->first();
        
        if (!$medico) {
            return redirect()->route('login')->with('error', 'No tienes permisos de médico.');
        }        // Verificar que el paciente existe y tiene citas con este médico
        $paciente = Usuarios::find($pacienteId);
        if (!$paciente) {
            return redirect()->route('medico.expedientes')->with('error', 'Paciente no encontrado.');
        }        // Verificar que el médico tenga citas con este paciente
        $tieneCitas = Cita::where('medico_id', $medico->id)
            ->where('paciente_id', $pacienteId)
            ->exists();

        if (!$tieneCitas) {
            return redirect()->route('medico.expedientes')->with('error', 'No tienes autorización para ver este expediente.');
        }        // Buscar el expediente personal existente o crear uno nuevo
        $expedientePersonal = PacienteExpediente::where('id_paciente', $pacienteId)->first();

        return view('medicos.expediente-personal', compact('paciente', 'medico', 'expedientePersonal'));
    }

    // Guardar expediente personal del paciente
    public function guardarExpedientePersonal(Request $request, $pacienteId)
    {
        $usuario = auth()->user();
        $medico = Medico::where('id', $usuario->id)->first();
        
        if (!$medico) {
            return redirect()->route('login')->with('error', 'No tienes permisos de médico.');
        }        // Verificar que el paciente existe
        $paciente = Usuarios::find($pacienteId);
        if (!$paciente) {
            return redirect()->route('medico.expedientes')->with('error', 'Paciente no encontrado.');
        }        // Validar los datos del formulario
        $request->validate([
            'fecha_nacimiento' => 'required|date|before:today',
            'sexo' => 'required|in:M,F,Otro',
            'direccion' => 'required|string',
            'telefono' => 'required|string|max:20',
            'enfermedades_cronicas' => 'nullable|string',
            'cirugias_previas' => 'nullable|string',
            'alergias' => 'nullable|string',
            'tratamientos_actuales' => 'nullable|string'
        ]);        // Actualizar o crear el expediente personal
        $expedientePersonal = PacienteExpediente::updateOrCreate(
            ['id_paciente' => $pacienteId],
            [
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'enfermedades_cronicas' => $request->enfermedades_cronicas,
                'cirugias_previas' => $request->cirugias_previas,
                'alergias' => $request->alergias,
                'tratamientos_actuales' => $request->tratamientos_actuales,
                'editado_por' => $medico->id
            ]
        );        return redirect()->route('medico.expedientePersonal', $pacienteId)
            ->with('success', 'Expediente personal actualizado correctamente.');
    }

    /**
     * Identifica las citas afectadas por cambios en horarios del médico
     * 
     * @param int $medicoId ID del médico
     * @param \Illuminate\Database\Eloquent\Collection $horariosExistentes Horarios actuales
     * @param array $nuevosHorarios Nuevos horarios a guardar
     * @return array IDs de citas afectadas
     */    private function identificarCitasAfectadasPorCambioHorario($medicoId, $horariosExistentes, $nuevosHorarios)
    {
        $citasAfectadas = [];
        
        \Log::info('Iniciando identificación de citas afectadas', [
            'medico_id' => $medicoId,
            'horarios_existentes_count' => $horariosExistentes->count(),
            'nuevos_horarios_count' => count($nuevosHorarios)
        ]);
        
        // Crear array de nuevos horarios para comparación más fácil
        $nuevosHorariosMap = [];
        foreach ($nuevosHorarios as $horario) {
            $key = $horario['dia_semana'] . '_' . $horario['clinica_id'];
            if (!isset($nuevosHorariosMap[$key])) {
                $nuevosHorariosMap[$key] = [];
            }
            $nuevosHorariosMap[$key][] = [
                'hora_inicio' => $horario['hora_inicio'],
                'hora_fin' => $horario['hora_fin']
            ];
        }
        
        \Log::info('Nuevos horarios mapeados:', $nuevosHorariosMap);
        
        // Verificar cada horario existente
        foreach ($horariosExistentes as $horarioExistente) {
            $key = $horarioExistente->dia_semana . '_' . $horarioExistente->clinica_id;
            $horarioEliminadoOModificado = false;
            
            \Log::info('Verificando horario existente:', [
                'id' => $horarioExistente->id,
                'dia_semana' => $horarioExistente->dia_semana,
                'clinica_id' => $horarioExistente->clinica_id,
                'hora_inicio' => $horarioExistente->hora_inicio,
                'hora_fin' => $horarioExistente->hora_fin,
                'key' => $key
            ]);
            
            // Verificar si este horario específico sigue existiendo
            if (!isset($nuevosHorariosMap[$key])) {
                // El día/clínica completo fue eliminado
                $horarioEliminadoOModificado = true;
                \Log::info('Horario completamente eliminado para: ' . $key);            } else {
                // Verificar si el rango de horas específico sigue existiendo
                $horaInicioExistente = $horarioExistente->hora_inicio instanceof \Carbon\Carbon 
                    ? $horarioExistente->hora_inicio->format('H:i') 
                    : substr($horarioExistente->hora_inicio, 0, 5);
                $horaFinExistente = $horarioExistente->hora_fin instanceof \Carbon\Carbon 
                    ? $horarioExistente->hora_fin->format('H:i') 
                    : substr($horarioExistente->hora_fin, 0, 5);
                
                $rangoEncontrado = false;
                foreach ($nuevosHorariosMap[$key] as $nuevoRango) {
                    if ($nuevoRango['hora_inicio'] === $horaInicioExistente && 
                        $nuevoRango['hora_fin'] === $horaFinExistente) {
                        $rangoEncontrado = true;
                        break;
                    }
                }
                
                if (!$rangoEncontrado) {
                    $horarioEliminadoOModificado = true;
                    \Log::info('Rango horario modificado:', [
                        'existente_inicio' => $horaInicioExistente,
                        'existente_fin' => $horaFinExistente,
                        'nuevos_rangos' => $nuevosHorariosMap[$key]
                    ]);
                }
            }
            
            // Si el horario fue eliminado o modificado, buscar citas afectadas
            if ($horarioEliminadoOModificado) {
                $citasEnEsteHorario = $this->buscarCitasEnHorario($medicoId, $horarioExistente);
                \Log::info('Citas encontradas en horario eliminado/modificado:', $citasEnEsteHorario);
                $citasAfectadas = array_merge($citasAfectadas, $citasEnEsteHorario);
            }
        }
        
        \Log::info('Citas afectadas totales:', array_unique($citasAfectadas));
        return array_unique($citasAfectadas);
    }
    
    /**
     * Busca citas aprobadas (no confirmadas) en un horario específico
     * 
     * @param int $medicoId ID del médico
     * @param \App\Models\DoctorSchedule $horario Horario a verificar
     * @return array IDs de citas en este horario
     */    private function buscarCitasEnHorario($medicoId, $horario)
    {
        // Mapear días de la semana a números (0=domingo, 1=lunes, etc.)
        $diasSemana = [
            'Domingo' => 0,
            'Lunes' => 1,
            'Martes' => 2,
            'Miércoles' => 3,
            'Jueves' => 4,
            'Viernes' => 5,
            'Sábado' => 6
        ];
        
        $numeroDia = $diasSemana[$horario->dia_semana] ?? null;
        if ($numeroDia === null) {
            \Log::warning('Día de semana no válido: ' . $horario->dia_semana);
            return [];
        }
        
        \Log::info('Buscando citas en horario:', [
            'horario_id' => $horario->id,
            'medico_id' => $medicoId,
            'dia_semana' => $horario->dia_semana,
            'numero_dia' => $numeroDia,
            'clinica_id' => $horario->clinica_id,
            'hora_inicio' => $horario->hora_inicio,
            'hora_fin' => $horario->hora_fin
        ]);
        
        // Buscar citas aprobadas (no confirmadas ni canceladas) del médico en este horario
        $citas = Cita::where('medico_id', $medicoId)
            ->where('clinica_id', $horario->clinica_id)
            ->whereIn('estado', ['aprobada', 'Aprobada']) // Solo citas aprobadas
            ->where('fecha', '>=', now()->toDateString()) // Solo citas futuras
            ->get();
        
        \Log::info('Citas candidatas encontradas: ' . $citas->count());
        
        $citasEnHorario = [];
        
        foreach ($citas as $cita) {
            $fechaCita = \Carbon\Carbon::parse($cita->fecha);
            $horaCita = \Carbon\Carbon::parse($cita->hora)->format('H:i');
            
            \Log::info('Evaluando cita:', [
                'cita_id' => $cita->id,
                'fecha' => $cita->fecha,
                'hora' => $horaCita,
                'dia_semana_fecha' => $fechaCita->dayOfWeek,
                'dia_requerido' => $numeroDia
            ]);
              // Verificar si la cita es en el día de la semana correcto
            if ($fechaCita->dayOfWeek === $numeroDia) {
                // Verificar si la hora de la cita está en el rango del horario
                $horaInicioHorario = $horario->hora_inicio instanceof \Carbon\Carbon 
                    ? $horario->hora_inicio->format('H:i') 
                    : substr($horario->hora_inicio, 0, 5);
                $horaFinHorario = $horario->hora_fin instanceof \Carbon\Carbon 
                    ? $horario->hora_fin->format('H:i') 
                    : substr($horario->hora_fin, 0, 5);
                
                \Log::info('Verificando rango horario:', [
                    'cita_hora' => $horaCita,
                    'horario_inicio' => $horaInicioHorario,
                    'horario_fin' => $horaFinHorario,
                    'en_rango' => ($horaCita >= $horaInicioHorario && $horaCita <= $horaFinHorario)
                ]);
                
                // Cambié < por <= para incluir la hora exacta de fin
                if ($horaCita >= $horaInicioHorario && $horaCita <= $horaFinHorario) {
                    $citasEnHorario[] = $cita->id;
                    \Log::info('Cita incluida en horario: ' . $cita->id);
                }
            }
        }
        
        \Log::info('Citas en horario resultado: ', $citasEnHorario);
        return $citasEnHorario;
    }
}
