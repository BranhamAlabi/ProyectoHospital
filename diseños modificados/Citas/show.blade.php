@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmoderador')

<div class="container mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white py-3">
            <h2 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Detalle de Cita</h2>
        </div>

        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="row">
                <!-- Información de la Cita -->
                <div class="col-md-6">
                    <div class="info-card mb-4">
                        <div class="info-header">
                            <i class="fas fa-info-circle me-2"></i>Información Básica
                        </div>
                        <div class="info-body">
                            <div class="info-row">
                                <span class="info-label">Estado:</span>
                                <span class="badge bg-{{ $cita->estado == 'aprobada' ? 'success' : ($cita->estado == 'cancelada' ? 'danger' : 'warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $cita->estado)) }}
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Paciente:</span>
                                <span>{{ $cita->paciente->nombre ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Médico:</span>
                                <span>{{ $cita->medico->nombre ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Clínica:</span>
                                <span>{{ $cita->clinica->nombre ?? 'No especificada' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-card mb-4">
                        <div class="info-header">
                            <i class="fas fa-clock me-2"></i>Horario
                        </div>
                        <div class="info-body">
                            <div class="info-row">
                                <span class="info-label">Fecha:</span>
                                <span>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Hora:</span>
                                <span>{{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="info-card mb-4">
                        <div class="info-header">
                            <i class="fas fa-sticky-note me-2"></i>Detalles
                        </div>
                        <div class="info-body">
                            <div class="info-row">
                                <span class="info-label">Motivo:</span>
                                <span>{{ $cita->motivo ?? 'No especificado' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Aprobado por:</span>
                                <span>{{ $cita->actualizador->nombre ?? 'No especificado' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Actualización -->
            <div class="card border-primary mt-4">
                <div class="card-header bg-primary text-white py-2">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Actualizar Estado</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('citas.updateStatus', $cita->id) }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Nuevo Estado</label>
                                <select name="estado" id="estado" class="form-select" required>
                                    <option value="aprobada" @selected($cita->estado == 'aprobada')>Aprobada</option>
                                    <option value="cancelada" @selected($cita->estado == 'cancelada')>Cancelada</option>
                                    <option value="pendiente" @selected($cita->estado == 'pendiente')>Pendiente</option>
                                    <option value="pendiente_reprogramacion" @selected($cita->estado == 'pendiente_reprogramacion')>Pendiente Reprogramación</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="comentarios" class="form-label">Comentarios</label>
                                <textarea name="comentarios" id="comentarios" class="form-control" rows="3">{{ old('comentarios', $cita->comentarios) }}</textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Actualizar Estado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .info-card {
        border: 1px solid #e0e0e0;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .info-header {
        background-color: #f8f9fa;
        padding: 0.75rem 1rem;
        font-weight: 600;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .info-body {
        padding: 1rem;
    }
    
    .info-row {
        display: flex;
        margin-bottom: 0.75rem;
    }
    
    .info-label {
        font-weight: 500;
        color: #495057;
        min-width: 120px;
    }
    
    .badge {
        font-size: 0.85rem;
        padding: 0.35em 0.65em;
    }
    
    .bg-success {
        background-color: #198754 !important;
    }
    
    .bg-danger {
        background-color: #dc3545 !important;
    }
    
    .bg-warning {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }
    
    @media (max-width: 768px) {
        .info-row {
            flex-direction: column;
        }
        
        .info-label {
            margin-bottom: 0.25rem;
        }
    }
</style>
@endsection