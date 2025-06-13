@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmedico')

<div class="container mt-4">
    <div class="row">
        <!-- Información del paciente -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Información del Paciente</h5>
                </div>
                <div class="card-body">
                    <h4>{{ $paciente->nombre }}</h4>
                    <p class="mb-2"><i class="fas fa-envelope"></i> {{ $paciente->correo }}</p>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total de citas:</span>
                        <span class="badge bg-primary">{{ $citas->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span>Última cita:</span>
                        <span class="badge bg-info">
                            {{ $citas->first() ? \Carbon\Carbon::parse($citas->first()->fecha)->format('d/m/Y') : 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de citas y notas -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Historial Médico</h5>
                </div>
                <div class="card-body">
                    @if($citas->count() > 0)
                        <div class="timeline">
                            @foreach($citas as $cita)
                            <div class="card mb-3 border-{{ 
                                $cita->estado == 'completada' ? 'success' : 
                                ($cita->estado == 'pendiente' ? 'warning' : 
                                ($cita->estado == 'confirmada' ? 'primary' : 'danger')) 
                            }}">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="fas fa-calendar"></i> 
                                            {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }} - 
                                            {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}
                                        </h6>
                                        <span class="badge bg-{{ 
                                            $cita->estado == 'completada' ? 'success' : 
                                            ($cita->estado == 'pendiente' ? 'warning' : 
                                            ($cita->estado == 'confirmada' ? 'primary' : 'danger')) 
                                        }}">
                                            {{ ucfirst($cita->estado) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Motivo:</strong> {{ $cita->motivo ?? 'No especificado' }}</p>
                                            <p><strong>Clínica:</strong> {{ $cita->clinica->nombre }}</p>
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            @if($cita->estado == 'pendiente' || $cita->estado == 'confirmada')
                                            <button class="btn btn-success btn-sm" onclick="cambiarEstado({{ $cita->id }}, 'completada')">
                                                <i class="fas fa-check"></i> Marcar como Completada
                                            </button>
                                            @endif
                                        </div>
                                    </div>

                                    @if($cita->notasMedicas)
                                    <hr>
                                    <div class="notas-medicas">
                                        <h6><i class="fas fa-notes-medical"></i> Notas Médicas</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Diagnóstico:</strong><br>
                                                {{ $cita->notasMedicas->diagnostico ?? 'No registrado' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Tratamiento:</strong><br>
                                                {{ $cita->notasMedicas->tratamiento ?? 'No registrado' }}</p>
                                            </div>
                                        </div>
                                        @if($cita->notasMedicas->observaciones)
                                        <p><strong>Observaciones:</strong><br>
                                        {{ $cita->notasMedicas->observaciones }}</p>
                                        @endif
                                    </div>
                                    @endif

                                    @if($cita->estado == 'completada' && !$cita->notasMedicas)
                                    <hr>
                                    <button class="btn btn-primary btn-sm" onclick="mostrarFormNotas({{ $cita->id }})">
                                        <i class="fas fa-plus"></i> Agregar Notas Médicas
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay citas registradas</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar notas médicas -->
<div class="modal fade" id="modalNotas" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Notas Médicas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNotas" action="" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Diagnóstico</label>
                        <textarea class="form-control" name="diagnostico" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tratamiento</label>
                        <textarea class="form-control" name="tratamiento" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notas Adicionales</label>
                        <textarea class="form-control" name="notas_adicionales" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarNotas()">Guardar Notas</button>
            </div>
        </div>
    </div>
</div>

<script>
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

function mostrarFormNotas(citaId) {
    const form = document.getElementById('formNotas');
    form.action = `/medico/citas/${citaId}/notas`;
    new bootstrap.Modal(document.getElementById('modalNotas')).show();
}

function guardarNotas() {
    document.getElementById('formNotas').submit();
}
</script>
@endsection
