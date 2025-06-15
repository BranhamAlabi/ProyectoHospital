@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navpaciente')

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-folder-medical"></i> Mis Expedientes Médicos</h5>
        </div>
        <div class="card-body">
            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <h4 class="text-primary">{{ $totalConsultas }}</h4>
                            <p class="mb-0">Total de Consultas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <h4 class="text-success">{{ $especialidadesVisitadas }}</h4>
                            <p class="mb-0">Especialidades Visitadas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-warning">
                        <div class="card-body text-center">
                            <h4 class="text-warning">{{ $medicosVisitados }}</h4>
                            <p class="mb-0">Médicos Consultados</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($expedientes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Médico</th>
                            <th>Especialidad</th>
                            <th>Clínica</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expedientes as $expediente)
                        <tr>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($expediente->fecha)->format('d/m/Y') }}</strong><br>
                                <small class="text-muted">{{ $expediente->hora }}</small>
                            </td>
                            <td>
                                <strong>Dr. {{ $expediente->medico->usuario->nombre ?? 'N/A' }}</strong>
                            </td>
                            <td>
                                {{ $expediente->medico->especialidades->first()->especialidad ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $expediente->clinica->nombre ?? 'N/A' }}
                            </td>
                            <td>
                                <span class="text-truncate" style="max-width: 200px; display: inline-block;" title="{{ $expediente->motivo }}">
                                    {{ Str::limit($expediente->motivo, 50) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    $expediente->estado == 'aprobada' ? 'success' : 
                                    ($expediente->estado == 'pendiente' ? 'warning' : 
                                    ($expediente->estado == 'confirmada' ? 'primary' : 'danger')) 
                                }}">
                                    {{ ucfirst($expediente->estado) }}
                                </span>
                            </td>
                            <td>
                                <button type="button" 
                                        class="btn btn-primary btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#expedienteModal{{ $expediente->id }}"
                                        onclick="cargarExpedienteDetalle({{ $expediente->id }})">
                                    <i class="fas fa-eye"></i> Ver expediente
                                </button>
                            </td>
                        </tr>

                        <!-- Modal para ver detalles del expediente -->
                        <div class="modal fade" id="expedienteModal{{ $expediente->id }}" tabindex="-1">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-file-medical"></i> Expediente Médico - {{ \Carbon\Carbon::parse($expediente->fecha)->format('d/m/Y') }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                        <div id="loading{{ $expediente->id }}" class="text-center py-5" style="display: none;">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Cargando...</span>
                                            </div>
                                            <p class="mt-3 text-muted">Cargando detalles del expediente...</p>
                                        </div>
                                        <div id="expedienteContent{{ $expediente->id }}">
                                            <!-- Información de la consulta -->
                                            <div class="card mb-3 border-primary">
                                                <div class="card-header bg-primary text-white">
                                                    <h6 class="mb-0">
                                                        <i class="fas fa-info-circle"></i> Información de la Consulta
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="border-start border-primary border-3 ps-3">
                                                                <small class="text-muted d-block">Fecha</small>
                                                                <strong>{{ \Carbon\Carbon::parse($expediente->fecha)->format('d/m/Y') }}</strong>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="border-start border-success border-3 ps-3">
                                                                <small class="text-muted d-block">Hora</small>
                                                                <strong>{{ $expediente->hora }}</strong>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="border-start border-warning border-3 ps-3">
                                                                <small class="text-muted d-block">Médico</small>
                                                                <strong>Dr. {{ $expediente->medico->usuario->nombre ?? 'N/A' }}</strong>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="border-start border-info border-3 ps-3">
                                                                <small class="text-muted d-block">Especialidad</small>
                                                                <strong>{{ $expediente->medico->especialidades->first()->especialidad ?? 'N/A' }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <div class="border-start border-secondary border-3 ps-3">
                                                                <small class="text-muted d-block">Clínica</small>
                                                                <strong>{{ $expediente->clinica->nombre ?? 'N/A' }}</strong>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="border-start border-dark border-3 ps-3">
                                                                <small class="text-muted d-block">Estado</small>
                                                                <span class="badge bg-{{ 
                                                                    $expediente->estado == 'aprobada' ? 'success' : 
                                                                    ($expediente->estado == 'pendiente' ? 'warning' : 
                                                                    ($expediente->estado == 'confirmada' ? 'primary' : 'danger')) 
                                                                }}">
                                                                    {{ ucfirst($expediente->estado) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-12">
                                                            <h6 class="text-primary">
                                                                <i class="fas fa-comment-medical"></i> Motivo de Consulta:
                                                            </h6>
                                                            <div class="border rounded p-3 bg-light">
                                                                {{ $expediente->motivo }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Notas médicas -->
                                            @if($expediente->medicalNotes->count() > 0)
                                                <div class="card border-success">
                                                    <div class="card-header bg-success text-white">
                                                        <h6 class="mb-0">
                                                            <i class="fas fa-notes-medical"></i> Notas Médicas
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        @foreach($expediente->medicalNotes as $nota)
                                                            <div class="border rounded p-3 mb-3 bg-light">
                                                                @if($nota->diagnostico)
                                                                    <div class="mb-3">
                                                                        <h6 class="text-info">
                                                                            <i class="fas fa-diagnoses"></i> Diagnóstico:
                                                                        </h6>
                                                                        <div style="white-space: pre-wrap;">{{ $nota->diagnostico }}</div>
                                                                    </div>
                                                                @endif
                                                                
                                                                @if($nota->tratamiento)
                                                                    <div class="mb-3">
                                                                        <h6 class="text-success">
                                                                            <i class="fas fa-pills"></i> Tratamiento:
                                                                        </h6>
                                                                        <div style="white-space: pre-wrap;">{{ $nota->tratamiento }}</div>
                                                                    </div>
                                                                @endif
                                                                
                                                                @if($nota->observaciones)
                                                                    <div class="mb-3">
                                                                        <h6 class="text-warning">
                                                                            <i class="fas fa-sticky-note"></i> Observaciones:
                                                                        </h6>
                                                                        <div style="white-space: pre-wrap;">{{ $nota->observaciones }}</div>
                                                                    </div>
                                                                @endif
                                                                
                                                                <small class="text-muted">
                                                                    <i class="fas fa-clock"></i> 
                                                                    Registrado el {{ $nota->created_at->format('d/m/Y H:i') }}
                                                                </small>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle"></i>
                                                    No hay notas médicas registradas para esta consulta.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times"></i> Cerrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-folder-medical fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No tienes expedientes médicos</h5>
                <p class="text-muted">Los expedientes aparecerán aquí una vez que tengas citas aprobadas y completadas.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function cargarExpedienteDetalle(expedienteId) {
    // Esta función puede ser expandida en el futuro para cargar información adicional vía AJAX
    console.log('Cargando detalles del expediente:', expedienteId);
}
</script>
@endpush
