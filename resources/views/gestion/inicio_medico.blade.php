@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmedico')

<div class="container-fluid mt-4">
    <!-- Header con información del médico -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body bg-primary-gradient text-white rounded-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1 fw-bold">Bienvenido, Dr. {{ $medico->usuario->nombre }}</h2>
                            <div class="d-flex flex-wrap align-items-center mt-2">
                                <span class="me-3"><i class="fas fa-stethoscope me-1"></i> Especialidades:</span>
                                @foreach($medico->especialidades as $especialidad)
                                    <span class="badge bg-white text-primary me-2 mb-1 px-2 py-1">{{ $especialidad->especialidad }}</span>
                                @endforeach
                            </div>
                            <div class="d-flex flex-wrap align-items-center mt-2">
                                <span class="me-3"><i class="fas fa-hospital me-1"></i> Clínicas:</span>
                                @foreach($medico->clinicas as $clinica)
                                    <span class="badge bg-light text-dark me-2 mb-1 px-2 py-1">{{ $clinica->nombre }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-user-md display-4 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas de mensajes -->
    <div class="row mb-3">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-3 fs-4"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                        <div>{{ session('warning') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-times-circle me-3 fs-4"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    </div>

    <!-- Notificaciones prioritarias -->
    @if($notificaciones->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-warning shadow-sm">
                <div class="card-header bg-warning bg-opacity-10 d-flex align-items-center">
                    <i class="fas fa-bell text-warning me-2 fs-5"></i>
                    <h5 class="mb-0 text-warning">Notificaciones Prioritarias</h5>
                </div>
                <div class="card-body">
                    @foreach($notificaciones as $notificacion)
                    <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mb-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong class="d-block">{{ $notificacion->titulo }}</strong>
                                <span class="d-block">{{ $notificacion->mensaje }}</span>
                                <small class="text-muted mt-1 d-block">
                                    <i class="far fa-clock me-1"></i>{{ $notificacion->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Estadísticas de la semana -->
<div style="max-width: 55%; margin: 0 auto; margin-bottom: 60px;">
    <div class="row mb-4 g-3">
        <div class="col-xl col-lg col-md col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-check-circle fs-3 text-success"></i>
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $citasAprobadasSemana }}</h3>
                    <p class="mb-0 text-muted small">Aprobadas esta semana</p>
                </div>
            </div>
        </div>
        <div class="col-xl col-lg col-md col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-check-double fs-3 text-primary"></i>
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $citasConfirmadasSemana }}</h3>
                    <p class="mb-0 text-muted small">Confirmadas esta semana</p>
                </div>
            </div>
        </div>
        <div class="col-xl col-lg col-md col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-clock fs-3 text-warning"></i>
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $citasPendientesSemana }}</h3>
                    <p class="mb-0 text-muted small">Pendientes esta semana</p>
                </div>
            </div>
        </div>
        <div class="col-xl col-lg col-md col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="bg-orange bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-calendar-times fs-3 text-orange"></i>
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $citasPendientesReprogramacionSemana }}</h3>
                    <p class="mb-0 text-muted small">Pend. reprogramación</p>
                </div>
            </div>
        </div>
        <div class="col-xl col-lg col-md col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-times-circle fs-3 text-danger"></i>
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $citasCanceladasSemana }}</h3>
                    <p class="mb-0 text-muted small">Canceladas esta semana</p>
                </div>
            </div>
        </div>
        <div class="col-xl col-lg col-md col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-calendar-week fs-3 text-info"></i>
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $citasAprobadasSemana + $citasConfirmadasSemana + $citasPendientesSemana + $citasPendientesReprogramacionSemana + $citasCanceladasSemana }}</h3>
                    <p class="mb-0 text-muted small">Total esta semana</p>
                </div>
            </div>
        </div>
        <div class="col-xl col-lg col-md col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-chart-line fs-2 text-secondary"></i>
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $totalCitas }}</h3>
                    <p class="mb-0 text-muted small">Total de citas históricas</p>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="row" style="margin: 50px;">
        <!-- Calendario de citas -->
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-alt text-primary me-2 fs-5"></i>
                        <h5 class="mb-0 fw-bold">Gestión de Citas</h5>
                        @if($filtro == 'semana')
                            <span class="badge bg-primary-soft text-primary ms-2">Esta Semana</span>
                        @elseif($filtro == 'proximas')
                            <span class="badge bg-primary-soft text-primary ms-2">Próximas</span>
                        @elseif($filtro == 'pasadas')
                            <span class="badge bg-primary-soft text-primary ms-2">Pasadas</span>
                        @elseif($filtro == 'fecha_custom')
                            <span class="badge bg-primary-soft text-primary ms-2">{{ $fechaCustom ? \Carbon\Carbon::parse($fechaCustom)->format('d/m/Y') : 'Fecha personalizada' }}</span>
                        @endif
                    </div>
                    <span class="badge bg-primary rounded-pill">{{ $citas->count() }} citas</span>
                </div>
                <div class="card-body">
                    <!-- Filtros de período -->
                    <div class="d-flex flex-wrap align-items-center mb-4 gap-2">
                        <div class="btn-group shadow-sm" role="group">
                            <a href="{{ route('medico.inicio', ['filtro' => 'semana']) }}" 
                               class="btn btn-sm {{ $filtro == 'semana' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-calendar-week me-1"></i> Esta Semana
                            </a>
                            <a href="{{ route('medico.inicio', ['filtro' => 'proximas']) }}" 
                               class="btn btn-sm {{ $filtro == 'proximas' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-calendar-plus me-1"></i> Próximas
                            </a>
                            <a href="{{ route('medico.inicio', ['filtro' => 'pasadas']) }}" 
                               class="btn btn-sm {{ $filtro == 'pasadas' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-calendar-minus me-1"></i> Pasadas
                            </a>
                        </div>
                        
                        <!-- Filtro de fecha personalizada -->
                        <form method="GET" action="{{ route('medico.inicio') }}" class="ms-auto">
                            <div class="input-group input-group-sm shadow-sm" style="width: 220px;">
                                <input type="hidden" name="filtro" value="fecha_custom">
                                <input type="date" name="fecha" class="form-control form-control-sm" 
                                       value="{{ $fechaCustom }}" placeholder="Fecha específica">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Filtros adicionales -->
                    <form method="GET" action="{{ route('medico.inicio') }}" id="form-filtros">
                        <input type="hidden" name="filtro" value="{{ $filtro }}">
                        @if($fechaCustom)
                            <input type="hidden" name="fecha" value="{{ $fechaCustom }}">
                        @endif
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control form-control-sm shadow-sm" id="paciente" name="paciente" 
                                           value="{{ request('paciente') }}" placeholder="Nombre del paciente">
                                    <label for="paciente"><i class="fas fa-user me-1"></i> <p style="margin-left: 26px;">Buscar por paciente</p></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-select form-select-sm shadow-sm" id="estado" name="estado">
                                        <option value="">Todos los estados</option>
                                        <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="aprobada" {{ request('estado') == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                                        <option value="Confirmada" {{ request('estado') == 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
                                        <option value="Cancelada" {{ request('estado') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                                        <option value="Pendiente_reprogramacion" {{ request('estado') == 'Pendiente_reprogramacion' ? 'selected' : '' }}>Pendiente reprogramación</option>
                                    </select>
                                    <label for="estado"><i class="fas fa-filter me-1"></i><p style="margin-left: 26px;">Estado</p> </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex gap-2 h-100 align-items-end">
                                    <button type="submit" class="btn btn-primary btn-sm shadow-sm flex-grow-1">
                                        <i class="fas fa-search me-1"></i> Filtrar
                                    </button>
                                    <a href="{{ route('medico.inicio', ['filtro' => $filtro]) }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                                        <i class="fas fa-eraser me-1"></i> Limpiar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tabla-citas">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3">Fecha</th>
                                    <th class="py-3">Hora</th>
                                    <th class="py-3">Paciente</th>
                                    <th class="py-3">Motivo</th>
                                    <th class="py-3">Estado</th>
                                    <th class="py-3 text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($citas as $cita)
                                <tr class="{{ \Carbon\Carbon::parse($cita->fecha)->isToday() ? 'table-active' : '' }}">
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</span>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($cita->fecha)->isoFormat('dddd') }}</small>
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $cita->paciente->nombre }}</strong>
                                            @if($cita->asistio !== null)
                                                <br>
                                                @if($cita->asistio === 1)
                                                    <small class="text-success">
                                                        <i class="fas fa-check-circle me-1"></i> Asistió
                                                    </small>
                                                @else
                                                    <small class="text-danger">
                                                        <i class="fas fa-times-circle me-1"></i> No asistió
                                                    </small>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $cita->motivo }}</td>
                                    <td>
                                        @php
                                            $estadoLower = strtolower(trim($cita->estado));
                                        @endphp
                                        <span class="badge rounded-pill bg-{{ 
                                            $estadoLower == 'confirmada' ? 'success' : 
                                            ($estadoLower == 'pendiente' ? 'warning' : 
                                            ($estadoLower == 'cancelada' ? 'danger' : 
                                            ($estadoLower == 'aprobada' ? 'info' :
                                            ($estadoLower == 'pendiente_reprogramacion' ? 'secondary' : 'light')))) 
                                        }} py-2 px-3">
                                            {{ $estadoLower == 'pendiente_reprogramacion' ? 'Pendiente reprogramación' : ucfirst($cita->estado) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        @if(in_array($estadoLower, ['confirmada', 'cancelada']))
                                            <!-- Cita ya procesada - no editable -->
                                            <span class="badge bg-light text-muted border py-2 px-3">
                                                <i class="fas fa-lock me-1"></i> Procesada
                                            </span>
                                        @else
                                            <!-- Cita editable -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary shadow-sm" 
                                                    data-cita-id="{{ $cita->id }}"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#actualizarCitaModal{{ $cita->id }}">
                                                <i class="fas fa-edit me-1"></i> Actualizar
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                @if(!in_array($estadoLower, ['confirmada', 'cancelada']))
                                <!-- Modal Actualizar Cita - Solo para citas editables -->
                                <div class="modal fade" id="actualizarCitaModal{{ $cita->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-edit text-primary me-2"></i>Actualizar Cita
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form id="formCita{{ $cita->id }}" action="{{ route('medico.actualizarEstadoCita', $cita->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="alert alert-info border-0 bg-info bg-opacity-10">
                                                        <i class="fas fa-info-circle text-info me-2"></i>
                                                        <strong>Nota:</strong> Una vez que confirmes o canceles esta cita, no podrás modificarla nuevamente.
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Paciente</label>
                                                        <input type="text" class="form-control" value="{{ $cita->paciente->nombre }}" readonly>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Estado</label>
                                                        <select class="form-select" name="estado" id="estado{{ $cita->id }}" required>
                                                            <option value="Pendiente" {{ $estadoLower == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                            <option value="Confirmada" {{ $estadoLower == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                                            <option value="Cancelada" {{ $estadoLower == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                                            <option value="Pendiente_reprogramacion" {{ $estadoLower == 'pendiente_reprogramacion' ? 'selected' : '' }}>Pendiente de reprogramación</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Comentarios</label>
                                                        <textarea class="form-control" name="comentarios" rows="3" placeholder="Opcional: Agregar comentarios sobre la cita">{{ $cita->comentarios }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-1"></i> Cancelar
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save me-1"></i> Guardar Cambios
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Expediente Médico -->
                                <div class="modal fade" id="expedienteMedicoModal{{ $cita->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-file-medical text-primary me-2"></i>Crear Expediente Médico
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('medico.guardarExpediente') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="cita_id" value="{{ $cita->id }}">
                                                <input type="hidden" name="paciente_id" value="{{ $cita->paciente_id }}">
                                                <input type="hidden" name="medico_id" value="{{ $cita->medico_id }}">
                                                <div class="modal-body">
                                                    <div class="alert alert-info border-0 bg-info bg-opacity-10 mb-4">
                                                        <div class="d-flex">
                                                            <i class="fas fa-info-circle text-info me-3 mt-1"></i>
                                                            <div>
                                                                <strong class="d-block">Paciente: {{ $cita->paciente->nombre }}</strong>
                                                                <span class="d-block">Fecha: {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</span>
                                                                <span class="d-block">Hora: {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Notas del Expediente</label>
                                                        <textarea class="form-control" name="notas" rows="8" 
                                                                  placeholder="Escriba las observaciones médicas, diagnósticos, tratamientos recomendados, etc." 
                                                                  required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-1"></i> Cerrar
                                                    </button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-save me-1"></i> Guardar Expediente
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                        
                        @if($citas->count() == 0)
                        <div class="text-center py-5">
                            <div class="py-4">
                                <i class="fas fa-calendar-times fa-4x text-muted mb-4 opacity-25"></i>
                                <h4 class="text-muted mb-3">No hay citas</h4>
                                <p class="text-muted mb-4">
                                    @if($filtro == 'semana')
                                        No tienes citas programadas para esta semana.
                                    @elseif($filtro == 'proximas')
                                        No tienes citas próximas programadas.
                                    @elseif($filtro == 'pasadas')
                                        No hay citas pasadas registradas.
                                    @else
                                        No se encontraron citas con los filtros aplicados.
                                    @endif
                                </p>
                                <a href="{{ route('medico.inicio', ['filtro' => 'semana']) }}" class="btn btn-primary px-4">
                                    <i class="fas fa-calendar-week me-2"></i> Ver citas de esta semana
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel lateral -->
        <div class="col-lg-2">
            <!-- Resumen rápido -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-chart-pie text-primary me-2"></i>Resumen de Hoy</h6>
                </div>
                <div class="card-body">
                    @php
                        // Filtrar citas de hoy usando la zona horaria configurada
                        $citasHoy = $citas->filter(function($cita) {
                            return \Carbon\Carbon::parse($cita->fecha)->isToday();
                        });
                        
                        // Contar por estados
                        $aprobadas = $citasHoy->filter(function($cita) {
                            $estado = strtolower(trim($cita->estado));
                            return $estado === 'aprobada';
                        })->count();
                        
                        $confirmadas = $citasHoy->filter(function($cita) {
                            $estado = strtolower(trim($cita->estado));
                            return $estado === 'confirmada';
                        })->count();
                        
                        $pendientes = $citasHoy->filter(function($cita) {
                            $estado = strtolower(trim($cita->estado));
                            return $estado === 'pendiente';
                        })->count();
                        
                        $pendientesReprogramacion = $citasHoy->filter(function($cita) {
                            $estado = strtolower(trim($cita->estado));
                            return $estado === 'pendiente_reprogramacion';
                        })->count();
                        
                        $canceladas = $citasHoy->filter(function($cita) {
                            $estado = strtolower(trim($cita->estado));
                            return $estado === 'cancelada';
                        })->count();
                    @endphp
                    <div class="row g-3 text-center">
                        <div class="col-6 col-md-4 col-lg-6">
                            <div class="p-3 rounded-3 bg-success bg-opacity-10">
                                <div class="text-success mb-2">
                                    <i class="fas fa-check-circle fs-4"></i>
                                </div>
                                <h5 class="mb-0 fw-bold">{{ $aprobadas }}</h5>
                                <small class="text-muted">Aprobadas</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-6">
                            <div class="p-3 rounded-3 bg-primary bg-opacity-10">
                                <div class="text-primary mb-2">
                                    <i class="fas fa-check-double fs-4"></i>
                                </div>
                                <h5 class="mb-0 fw-bold">{{ $confirmadas }}</h5>
                                <small class="text-muted">Confirmadas</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-6">
                            <div class="p-3 rounded-3 bg-warning bg-opacity-10">
                                <div class="text-warning mb-2">
                                    <i class="fas fa-clock fs-4"></i>
                                </div>
                                <h5 class="mb-0 fw-bold">{{ $pendientes }}</h5>
                                <small class="text-muted">Pendientes</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-6">
                            <div class="p-3 rounded-3 bg-orange bg-opacity-10">
                                <div class="text-orange mb-2">
                                    <i class="fas fa-calendar-times fs-4"></i>
                                </div>
                                <h5 class="mb-0 fw-bold">{{ $pendientesReprogramacion }}</h5>
                                <small class="text-muted">Pend. reprogramación</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-12">
                            <div class="p-3 rounded-3 bg-danger bg-opacity-10">
                                <div class="text-danger mb-2">
                                    <i class="fas fa-times-circle fs-4"></i>
                                </div>
                                <h5 class="mb-0 fw-bold">{{ $canceladas }}</h5>
                                <small class="text-muted">Canceladas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horario del día -->
            <div class="card border-0 shadow-sm" style="margin-top: 50px; margin-bottom: 70px;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-clock text-primary me-2"></i>Horario de Hoy</h6>
                </div>
                <div class="card-body">
                    @php
                        $citasHoyOrdenadas = $citasHoy->sortBy('hora');
                    @endphp

                    @if($citasHoyOrdenadas->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($citasHoyOrdenadas as $cita)
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex align-items-center">
                                    <div class="me-3 text-center">
                                        <span class="badge bg-primary rounded-pill px-3 py-2">
                                            {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $cita->paciente->nombre }}</h6>
                                        <small class="text-muted">{{ $cita->motivo }}</small>
                                    </div>
                                    <div>
                                        @php
                                            $estadoLower = strtolower(trim($cita->estado));
                                        @endphp
                                        <span class="badge rounded-pill bg-{{ 
                                            $estadoLower == 'confirmada' ? 'success' : 
                                            ($estadoLower == 'pendiente' ? 'warning' : 
                                            ($estadoLower == 'cancelada' ? 'danger' : 
                                            ($estadoLower == 'aprobada' ? 'info' :
                                            ($estadoLower == 'pendiente_reprogramacion' ? 'secondary' : 'light')))) 
                                        }} py-1 px-2">
                                            {{ $estadoLower == 'pendiente_reprogramacion' ? 'Reprog.' : ucfirst($cita->estado) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted mb-0">No hay citas programadas para hoy</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-primary-gradient {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    }
    
    .bg-orange {
        color: #fd7e14;
    }
    
    .bg-primary-soft {
        background-color: rgba(59, 130, 246, 0.1);
    }
    
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .badge {
        font-weight: 500;
    }
    
    .form-floating label {
        padding-left: 2.5rem;
    }
    
    .form-floating .form-control, 
    .form-floating .form-select {
        padding-left: 2.5rem;
    }
    
    .form-floating i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        z-index: 5;
        color: #6c757d;
    }
    
    .empty-state {
        background-color: #f8f9fa;
        border-radius: 12px;
    }
    
    .list-group-item {
        border-left: 0;
        border-right: 0;
    }
    
    .list-group-item:first-child {
        border-top: 0;
    }
    
    .list-group-item:last-child {
        border-bottom: 0;
    }
    
    .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.03);
    }
    
    .table-active {
        background-color: rgba(59, 130, 246, 0.05) !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[id^="formCita"]');
    
    forms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const citaId = form.id.replace('formCita', '');
            const formData = new FormData(form);
            const estado = formData.get('estado');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (response.ok && result.success) {
                    // Cerrar el modal de actualización
                    const actualizarModal = bootstrap.Modal.getInstance(document.getElementById('actualizarCitaModal' + citaId));
                    if (actualizarModal) {
                        actualizarModal.hide();
                    }
                    
                    // Mostrar mensaje de éxito
                    alert(result.message || 'Cita actualizada correctamente');
                    
                    // Si se debe mostrar el modal de expediente
                    if (result.mostrar_expediente) {
                        setTimeout(() => {
                            const expedienteModal = new bootstrap.Modal(document.getElementById('expedienteMedicoModal' + citaId));
                            expedienteModal.show();
                        }, 500);
                    } else {
                        // Solo recargar si no se va a mostrar el modal de expediente
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                } else {
                    throw new Error(result.message || 'Error al actualizar la cita');
                }
            } catch (error) {
                console.error('Error:', error);
                alert(error.message || 'Error al actualizar la cita');
            }
        });
    });
    
    // Mostrar modal de expediente si viene de redirección
    @if(session()->has('mostrar_expediente') && session()->has('cita_id'))
        const expedienteModal = new bootstrap.Modal(document.getElementById('expedienteMedicoModal{{ session('cita_id') }}'));
        expedienteModal.show();
    @endif
});

// Funciones para filtros de citas
function aplicarFiltros() {
    const filtroPaciente = document.getElementById('filtro-paciente').value.toLowerCase();
    const filtroEstado = document.getElementById('filtro-estado').value;
    const filtroFecha = document.getElementById('filtro-fecha').value;
    
    const tabla = document.getElementById('tabla-citas');
    const filas = tabla.querySelectorAll('tbody tr');
    
    filas.forEach(fila => {
        // Obtener datos de la fila (excluyendo modales)
        if (fila.style.display === 'none' || fila.querySelector('.modal')) {
            return; // Saltar modales y filas ya ocultas
        }
        
        const celdas = fila.querySelectorAll('td');
        if (celdas.length < 5) return; // Saltar si no tiene suficientes celdas
        
        const fechaCita = celdas[0].textContent.trim();
        const paciente = celdas[2].textContent.toLowerCase().trim();
        const estadoBadge = celdas[4].querySelector('.badge');
        const estado = estadoBadge ? estadoBadge.textContent.trim() : '';
        
        let mostrar = true;
        
        // Filtro por paciente
        if (filtroPaciente && !paciente.includes(filtroPaciente)) {
            mostrar = false;
        }
        
        // Filtro por estado
        if (filtroEstado && estado !== filtroEstado) {
            mostrar = false;
        }
        
        // Filtro por fecha
        if (filtroFecha) {
            const fechaFiltro = new Date(filtroFecha);
            const fechaCitaParts = fechaCita.split('/');
            const fechaCitaDate = new Date(fechaCitaParts[2], fechaCitaParts[1] - 1, fechaCitaParts[0]);
            
            if (fechaCitaDate.toDateString() !== fechaFiltro.toDateString()) {
                mostrar = false;
            }
        }
        
        fila.style.display = mostrar ? '' : 'none';
    });
    
    // Actualizar contador de resultados
    actualizarContadorResultados();
}

function limpiarFiltros() {
    document.getElementById('filtro-paciente').value = '';
    document.getElementById('filtro-estado').value = '';
    document.getElementById('filtro-fecha').value = '';
    
    // Mostrar todas las filas
    const tabla = document.getElementById('tabla-citas');
    const filas = tabla.querySelectorAll('tbody tr');
    
    filas.forEach(fila => {
        if (!fila.querySelector('.modal')) {
            fila.style.display = '';
        }
    });
    
    actualizarContadorResultados();
}

function actualizarContadorResultados() {
    const tabla = document.getElementById('tabla-citas');
    const filasVisibles = Array.from(tabla.querySelectorAll('tbody tr')).filter(fila => 
        fila.style.display !== 'none' && !fila.querySelector('.modal')
    );
    
    // Crear o actualizar el contador si no existe
    let contador = document.getElementById('contador-resultados');
    if (!contador) {
        contador = document.createElement('div');
        contador.id = 'contador-resultados';
        contador.className = 'text-muted mb-2';
        tabla.parentNode.insertBefore(contador, tabla);
    }
    
    contador.innerHTML = `<small><i class="fas fa-info-circle"></i> Mostrando ${filasVisibles.length} cita(s)</small>`;
}

// Aplicar filtros al escribir (con debounce)
let timeoutId;
document.addEventListener('DOMContentLoaded', function() {
    const filtroPaciente = document.getElementById('filtro-paciente');
    if (filtroPaciente) {
        filtroPaciente.addEventListener('input', function() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(aplicarFiltros, 300);
        });
    }
    
    const filtroEstado = document.getElementById('filtro-estado');
    if (filtroEstado) {
        filtroEstado.addEventListener('change', aplicarFiltros);
    }
    
    const filtroFecha = document.getElementById('filtro-fecha');
    if (filtroFecha) {
        filtroFecha.addEventListener('change', aplicarFiltros);
    }
    
    // Inicializar contador
    actualizarContadorResultados();
});

// Auto-submit form cuando cambia el filtro de fecha personalizada
document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.querySelector('input[name="fecha"]');
    if (fechaInput) {
        fechaInput.addEventListener('change', function() {
            if (this.value) {
                this.closest('form').submit();
            }
        });
    }
});
</script>
@endpush