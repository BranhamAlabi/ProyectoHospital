@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navpaciente')

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt"></i> Mis Citas Médicas
                        </h5>
                        <a href="{{ route('paciente.citas.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus"></i> Nueva Cita
                        </a>
                    </div>
                </div>                <!-- Estadísticas rápidas -->
                <div class="card-body bg-light border-bottom">
                    <div class="row text-center">
                        <div class="col-md-2">
                            <div class="card border-0 bg-info text-white">
                                <div class="card-body py-2">
                                    <h4 class="mb-0">{{ $totalCitas }}</h4>
                                    <small>Total</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card border-0 bg-success text-white">
                                <div class="card-body py-2">
                                    <h4 class="mb-0">{{ $citasAprobadas ?? 0 }}</h4>
                                    <small>Aprobadas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card border-0 bg-primary text-white">
                                <div class="card-body py-2">
                                    <h4 class="mb-0">{{ $citasConfirmadas ?? 0 }}</h4>
                                    <small>Confirmadas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card border-0 bg-warning text-white">
                                <div class="card-body py-2">
                                    <h4 class="mb-0">{{ $citasPendientes ?? 0 }}</h4>
                                    <small>Pendientes</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card border-0 bg-dark text-white">
                                <div class="card-body py-2">
                                    <h4 class="mb-0">{{ $citasPendientesReprog ?? 0 }}</h4>
                                    <small>Pend. Reprog.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card border-0 bg-secondary text-white">
                                <div class="card-body py-2">
                                    <h4 class="mb-0">{{ $citasCanceladas ?? 0 }}</h4>
                                    <small>Canceladas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('paciente.citas.index') }}" id="filtrosForm">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label"><i class="fas fa-flag"></i> Estado</label>                                <select name="estado" class="form-select" onchange="document.getElementById('filtrosForm').submit();">
                                    <option value="">Todos</option>
                                    <option value="aprobada" {{ request('estado') == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                                    <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="pendiente_reprogramacion" {{ request('estado') == 'pendiente_reprogramacion' ? 'selected' : '' }}>Pendiente Reprogramación</option>
                                    <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label"><i class="fas fa-calendar"></i> Desde</label>
                                <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}" onchange="document.getElementById('filtrosForm').submit();">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label"><i class="fas fa-calendar"></i> Hasta</label>
                                <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}" onchange="document.getElementById('filtrosForm').submit();">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label"><i class="fas fa-user-md"></i> Médico</label>
                                <input type="text" name="medico" class="form-control" placeholder="Buscar médico..." value="{{ request('medico') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label"><i class="fas fa-stethoscope"></i> Especialidad</label>
                                <input type="text" name="especialidad" class="form-control" placeholder="Especialidad..." value="{{ request('especialidad') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label"><i class="fas fa-hospital"></i> Clínica</label>
                                <input type="text" name="clinica" class="form-control" placeholder="Buscar clínica..." value="{{ request('clinica') }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <a href="{{ route('paciente.citas.index') }}" class="btn btn-secondary btn-sm ms-2">
                                    <i class="fas fa-times"></i> Limpiar Filtros
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Lista de citas -->
                <div class="card-body">
                    @if($citas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-calendar"></i> Fecha/Hora</th>
                                        <th><i class="fas fa-user-md"></i> Médico</th>
                                        <th><i class="fas fa-stethoscope"></i> Especialidad</th>
                                        <th><i class="fas fa-hospital"></i> Clínica</th>
                                        <th><i class="fas fa-comment"></i> Motivo</th>
                                        <th><i class="fas fa-flag"></i> Estado</th>
                                        <th><i class="fas fa-cogs"></i> Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($citas as $cita)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $cita->hora }}</small>
                                            </td>                                            <td>
                                                @if($cita->medico && $cita->medico->usuario)
                                                    <div class="fw-bold">{{ $cita->medico->usuario->nombre }}</div>
                                                @else
                                                    <div class="fw-bold text-muted">N/A</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($cita->medico && $cita->medico->especialidades && $cita->medico->especialidades->count() > 0)
                                                    {{ $cita->medico->especialidades->first()->especialidad }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $cita->clinica->nombre ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <span title="{{ $cita->motivo }}">
                                                    {{ Str::limit($cita->motivo, 30) }}
                                                </span>
                                            </td>                                            <td>
                                                @php
                                                    $estadoLower = strtolower($cita->estado);
                                                @endphp                                                <span class="badge bg-{{ 
                                                    $estadoLower == 'aprobada' ? 'success' : 
                                                    ($estadoLower == 'confirmada' ? 'primary' : 
                                                    ($estadoLower == 'pendiente' ? 'warning' : 
                                                    ($estadoLower == 'pendiente_reprogramacion' ? 'dark' : 
                                                    ($estadoLower == 'cancelada' ? 'secondary' : 'light')))) 
                                                }}">
                                                    {{ $estadoLower == 'pendiente_reprogramacion' ? 'Pendiente reprogramación' : ucfirst($cita->estado) }}
                                                </span>
                                                @if($cita->asistio !== null)
                                                    <br><small class="text-muted">
                                                        Asistió: {{ $cita->asistio ? 'Sí' : 'No' }}
                                                    </small>
                                                @endif
                                            </td>                                            <td>
                                                @php
                                                    $estadoLower = strtolower($cita->estado);
                                                @endphp
                                                <div class="btn-group" role="group">
                                                    
                                                      <!-- Cancelar (solo si está pendiente, aprobada, o pendiente_reprogramacion y es futura) -->
                                                    @if(in_array($estadoLower, ['pendiente', 'aprobada', 'pendiente_reprogramacion']) && \Carbon\Carbon::parse($cita->fecha)->isFuture())
                                                        <button class="btn btn-danger btn-sm" onclick="cancelarCita({{ $cita->id }})" title="Cancelar">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif                                                    <!-- Reprogramar (solo si está aprobada, pendiente, o pendiente_reprogramacion y es futura) -->
                                                    @if(in_array($estadoLower, ['aprobada', 'pendiente', 'pendiente_reprogramacion']) && \Carbon\Carbon::parse($cita->fecha)->isFuture())
                                                        <a href="{{ route('paciente.citas.reprogramar', $cita->id) }}" class="btn btn-warning btn-sm" title="Reprogramar" onclick="return confirm('¿Está seguro que desea reprogramar esta cita?')">
                                                            <i class="fas fa-calendar-alt"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-muted">
                                    Mostrando {{ $citas->firstItem() }} a {{ $citas->lastItem() }} de {{ $citas->total() }} citas
                                </small>
                            </div>
                            <div>
                                {{ $citas->withQueryString()->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No se encontraron citas</h5>
                            @if(request()->hasAny(['estado', 'fecha_desde', 'fecha_hasta', 'medico', 'especialidad', 'clinica']))
                                <p class="text-muted">Intenta ajustar los filtros de búsqueda.</p>
                                <a href="{{ route('paciente.citas.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Limpiar Filtros
                                </a>
                            @else
                                <p class="text-muted">Aún no tienes citas programadas.</p>
                                <a href="{{ route('paciente.citas.create') }}" class="btn btn-primary">
                                    <i class="fas fa-calendar-plus"></i> Agendar Primera Cita
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles de la cita -->
<div class="modal fade" id="modalDetalleCita" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-calendar-alt"></i> Detalles de la Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoDetalleCita">
                <!-- El contenido se cargará dinámicamente -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function verDetalleCita(citaId) {
    // Por ahora mostrar un modal simple, después puedes implementar AJAX
    $('#modalDetalleCita').modal('show');
    document.getElementById('contenidoDetalleCita').innerHTML = '<p>Funcionalidad en desarrollo...</p>';
}

function cancelarCita(citaId) {
    if (confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
        fetch(`/paciente/citas/${citaId}/cancelar`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cita cancelada exitosamente');
                location.reload();
            } else {
                alert('Error al cancelar la cita: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cancelar la cita');
        });
    }
}


</script>
@endpush

@push('styles')
<style>
.table td {
    vertical-align: middle;
}

.btn-group .btn {
    margin-right: 2px;
}

.badge {
    font-size: 0.85em;
}

.card .bg-info { background-color: #17a2b8 !important; }
.card .bg-success { background-color: #28a745 !important; }
.card .bg-warning { background-color: #ffc107 !important; }
.card .bg-secondary { background-color: #6c757d !important; }
</style>
@endpush

@endsection
