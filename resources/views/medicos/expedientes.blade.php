@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmedico')

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-folder-open"></i> Expedientes de Pacientes</h5>
        </div>
        <div class="card-body">
            @if($pacientes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>Correo</th>
                            <th>Última Cita</th>
                            <th>Total Citas</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pacientes as $paciente)
                        @php
                            $ultimaCita = $paciente->citasPaciente->first();
                            $totalCitas = $paciente->citasPaciente->count();
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $paciente->nombre }}</strong>
                            </td>
                            <td>{{ $paciente->correo }}</td>
                            <td>
                                @if($ultimaCita)
                                    {{ \Carbon\Carbon::parse($ultimaCita->fecha)->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">Sin citas</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $totalCitas }}</span>
                            </td>                            <td>
                                @if($ultimaCita)
                                    @php
                                        $estadoLower = strtolower(trim($ultimaCita->estado));
                                    @endphp
                                    <span class="badge bg-{{ 
                                        $estadoLower == 'completada' ? 'success' : 
                                        ($estadoLower == 'pendiente' ? 'warning' : 
                                        ($estadoLower == 'confirmada' ? 'primary' : 'danger')) 
                                    }}">
                                        {{ ucfirst($ultimaCita->estado) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Sin citas</span>
                                @endif
                            </td><td>
                                <button type="button" 
                                        class="btn btn-primary btn-sm me-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#expedienteModal{{ $paciente->id }}"
                                        onclick="cargarExpedientes({{ $paciente->id }})">
                                    <i class="fas fa-eye"></i> Ver expediente
                                </button>
                                <a href="{{ route('medico.expedientePersonal', $paciente->id) }}" 
                                   class="btn btn-success btn-sm">
                                    <i class="fas fa-user-edit"></i> Complementar expediente personal
                                </a>
                            </td></tr>                        <!-- Modal para ver expedientes del paciente -->
                        <div class="modal fade" id="expedienteModal{{ $paciente->id }}" tabindex="-1">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-user-md"></i> Expedientes Médicos de {{ $paciente->nombre }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                        <div id="loading{{ $paciente->id }}" class="text-center py-5" style="display: none;">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Cargando...</span>
                                            </div>
                                            <p class="mt-3 text-muted">Cargando expedientes médicos...</p>
                                        </div>
                                        <div id="expedientesContent{{ $paciente->id }}">
                                            <!-- Aquí se cargarán los expedientes vía AJAX -->
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
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay pacientes registrados</h5>
                <p class="text-muted">Los pacientes aparecerán aquí una vez que tengan citas programadas contigo.</p>
            </div>
            @endif
        </div>    </div>
</div>
@endsection

@push('scripts')
<script>
async function cargarExpedientes(pacienteId) {
    const loadingDiv = document.getElementById('loading' + pacienteId);
    const contentDiv = document.getElementById('expedientesContent' + pacienteId);
    
    // Mostrar loading
    loadingDiv.style.display = 'block';
    contentDiv.innerHTML = '';
    
    try {
        const response = await fetch(`/medico/expedientes/${pacienteId}/lista`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            loadingDiv.style.display = 'none';
              if (data.expedientes && data.expedientes.length > 0) {
                let html = `
                    <div class="mb-3">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Se encontraron <strong>${data.expedientes.length}</strong> expediente(s) para este paciente.
                        </div>
                    </div>
                `;
                
                data.expedientes.forEach((expediente, index) => {
                    html += `
                        <div class="card mb-3 border-primary">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-file-medical"></i> 
                                    Expediente #${index + 1}
                                </h6>
                                <small>
                                    <i class="fas fa-clock"></i> ${expediente.created_at}
                                </small>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="border-start border-primary border-3 ps-3">
                                            <small class="text-muted d-block">Fecha de la Cita</small>
                                            <strong>${expediente.fecha_cita}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-success border-3 ps-3">
                                            <small class="text-muted d-block">Hora</small>
                                            <strong>${expediente.hora_cita}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-warning border-3 ps-3">
                                            <small class="text-muted d-block">Motivo</small>
                                            <strong>${expediente.motivo_cita}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-primary">
                                            <i class="fas fa-notes-medical"></i> Notas del Expediente:
                                        </h6>
                                        <div class="border rounded p-3 bg-light">
                                            <div style="white-space: pre-wrap;">${expediente.notas}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                });
                
                contentDiv.innerHTML = html;            } else {
                contentDiv.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-file-medical fa-4x text-muted mb-4"></i>
                        <h5 class="text-muted">No hay expedientes médicos</h5>
                        <p class="text-muted">Este paciente aún no tiene expedientes médicos registrados.</p>
                        <small class="text-muted">
                            Los expedientes se crean automáticamente cuando confirmas una cita del paciente.
                        </small>
                    </div>`;
            }
        } else {
            throw new Error('Error al cargar los expedientes');
        }
    } catch (error) {
        console.error('Error:', error);
        loadingDiv.style.display = 'none';
        contentDiv.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                Error al cargar los expedientes. Por favor, intenta nuevamente.
            </div>`;
    }
}
</script>
@endpush
