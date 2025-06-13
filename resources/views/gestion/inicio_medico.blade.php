@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmedico')

<div class="container-fluid mt-4">
    <!-- Header con información del médico -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h2 class="mb-0">Bienvenido, Dr. {{ $medico->usuario->nombre }}</h2>
                    <p class="mb-0">
                        Especialidades: 
                        @foreach($medico->especialidades as $especialidad)
                            <span class="badge bg-light text-dark me-1">{{ $especialidad->especialidad }}</span>
                        @endforeach
                    </p>
                    <p class="mb-0">
                        Clínicas: 
                        @foreach($medico->clinicas as $clinica)
                            <span class="badge bg-secondary me-1">{{ $clinica->nombre }}</span>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Notificaciones prioritarias -->
    @if($notificaciones->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-bell"></i> Notificaciones Prioritarias</h5>
                </div>
                <div class="card-body">
                    @foreach($notificaciones as $notificacion)
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong>{{ $notificacion->titulo }}</strong><br>
                        {{ $notificacion->mensaje }}
                        <small class="text-muted d-block">{{ $notificacion->created_at->diffForHumans() }}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Calendario de citas -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Agenda Semanal</h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="cambiarVista('dia')">Día</button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="cambiarVista('semana')">Semana</button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="cambiarVista('mes')">Mes</button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" id="filtroEstado">
                                <option value="">Todos los estados</option>
                                <option value="pendiente">Pendientes</option>
                                <option value="confirmada">Confirmadas</option>
                                <option value="cancelada">Canceladas</option>
                                <option value="completada">Completadas</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control form-control-sm" id="filtroMotivo" placeholder="Filtrar por motivo">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-sm btn-outline-secondary" onclick="limpiarFiltros()">Limpiar filtros</button>
                        </div>
                    </div>

                    <!-- Calendario -->
                    <div id="calendario-citas">
                        @if($citas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Paciente</th>
                                        <th>Motivo</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($citas as $cita)
                                    <tr class="cita-row" data-estado="{{ $cita->estado }}" data-motivo="{{ $cita->motivo }}">
                                        <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</td>
                                        <td>{{ $cita->paciente->nombre }}</td>
                                        <td>{{ $cita->motivo ?? 'Sin especificar' }}</td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $cita->estado == 'confirmada' ? 'success' : 
                                                ($cita->estado == 'pendiente' ? 'warning' : 
                                                ($cita->estado == 'cancelada' ? 'danger' : 'info')) 
                                            }}">
                                                {{ ucfirst($cita->estado) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if($cita->estado == 'pendiente')
                                                <button class="btn btn-success btn-sm" onclick="cambiarEstado({{ $cita->id }}, 'confirmada')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                @endif
                                                <button class="btn btn-info btn-sm" onclick="verDetalleCita({{ $cita->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-warning btn-sm" onclick="editarCita({{ $cita->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No tienes citas programadas para esta semana</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel lateral -->
        <div class="col-lg-4">
            <!-- Resumen rápido -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-pie"></i> Resumen de Hoy</h6>
                </div>
                <div class="card-body">
                    @php
                        $citasHoy = $citas->filter(function($cita) {
                            return \Carbon\Carbon::parse($cita->fecha)->isToday();
                        });
                        $pendientes = $citasHoy->where('estado', 'pendiente')->count();
                        $confirmadas = $citasHoy->where('estado', 'confirmada')->count();
                        $completadas = $citasHoy->where('estado', 'completada')->count();
                    @endphp
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="text-warning">
                                <i class="fas fa-clock fa-2x"></i>
                                <div class="mt-1">
                                    <strong>{{ $pendientes }}</strong><br>
                                    <small>Pendientes</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-success">
                                <i class="fas fa-check-circle fa-2x"></i>
                                <div class="mt-1">
                                    <strong>{{ $confirmadas }}</strong><br>
                                    <small>Confirmadas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-info">
                                <i class="fas fa-user-check fa-2x"></i>
                                <div class="mt-1">
                                    <strong>{{ $completadas }}</strong><br>
                                    <small>Completadas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accesos rápidos -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-bolt"></i> Accesos Rápidos</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('medico.expedientes') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-folder-open"></i> Ver Expedientes
                        </a>
                        <a href="{{ route('medico.horarios') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-clock"></i> Configurar Horarios
                        </a>
                        <button class="btn btn-outline-info btn-sm" onclick="generarReporte()">
                            <i class="fas fa-chart-bar"></i> Generar Reporte
                        </button>
                    </div>
                </div>
            </div>

            <!-- Próximas citas -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-clock"></i> Próximas Citas</h6>
                </div>
                <div class="card-body">
                    @php
                        $proximasCitas = $citas->filter(function($cita) {
                            return \Carbon\Carbon::parse($cita->fecha . ' ' . $cita->hora)->isFuture();
                        })->take(3);
                    @endphp
                    
                    @if($proximasCitas->count() > 0)
                        @foreach($proximasCitas as $cita)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                            <div>
                                <strong>{{ $cita->paciente->nombre }}</strong><br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m') }} - 
                                    {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}
                                </small>
                            </div>
                            <span class="badge bg-primary">
                                {{ \Carbon\Carbon::parse($cita->fecha . ' ' . $cita->hora)->diffForHumans() }}
                            </span>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No hay citas próximas</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalles de cita -->
<div class="modal fade" id="modalDetalleCita" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoDetalleCita">
                <!-- Contenido cargado dinámicamente -->
            </div>
        </div>
    </div>
</div>

<script>
function cambiarVista(vista) {
    // Implementar cambio de vista del calendario
    console.log('Cambiar vista a:', vista);
}

function cambiarEstado(citaId, nuevoEstado) {
    if (confirm('¿Está seguro de cambiar el estado de esta cita?')) {
        fetch(`/medico/citas/${citaId}/estado`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ estado: nuevoEstado })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al actualizar el estado');
            }
        });
    }
}

function verDetalleCita(citaId) {
    // Cargar detalles de la cita en el modal
    fetch(`/citas/${citaId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('contenidoDetalleCita').innerHTML = html;
            new bootstrap.Modal(document.getElementById('modalDetalleCita')).show();
        });
}

function editarCita(citaId) {
    // Redirigir a edición de cita
    window.location.href = `/citas/${citaId}/edit`;
}

function limpiarFiltros() {
    document.getElementById('filtroEstado').value = '';
    document.getElementById('filtroMotivo').value = '';
    filtrarCitas();
}

function filtrarCitas() {
    const estado = document.getElementById('filtroEstado').value;
    const motivo = document.getElementById('filtroMotivo').value.toLowerCase();
    
    document.querySelectorAll('.cita-row').forEach(row => {
        const estadoCita = row.dataset.estado;
        const motivoCita = row.dataset.motivo.toLowerCase();
        
        const mostrar = (estado === '' || estadoCita === estado) && 
                       (motivo === '' || motivoCita.includes(motivo));
        
        row.style.display = mostrar ? '' : 'none';
    });
}

function generarReporte() {
    // Implementar generación de reportes
    alert('Función de reportes en desarrollo');
}

// Event listeners
document.getElementById('filtroEstado').addEventListener('change', filtrarCitas);
document.getElementById('filtroMotivo').addEventListener('input', filtrarCitas);
</script>
@endsection
