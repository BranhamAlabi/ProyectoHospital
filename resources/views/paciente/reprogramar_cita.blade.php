@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navpaciente')

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="fas fa-calendar-edit me-2"></i>Reprogramar Cita
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('paciente.inicio') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('paciente.citas.index') }}">Mis Citas</a></li>
        <li class="breadcrumb-item active">Reprogramar Cita</li>
    </ol>

    <div class="row">
        <!-- Información de la Cita Original -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Cita Original
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Fecha Actual:</strong><br>
                        <span class="text-muted">{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Hora Actual:</strong><br>
                        <span class="text-muted">{{ $cita->hora }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Médico:</strong><br>
                        <span class="text-muted">Dr. {{ $cita->medico->usuario->nombre ?? 'N/A' }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Especialidad:</strong><br>
                        <span class="text-muted">{{ $cita->medico->especialidades->first()->especialidad ?? 'N/A' }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Clínica:</strong><br>
                        <span class="text-muted">{{ $cita->clinica->nombre ?? 'N/A' }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Motivo:</strong><br>
                        <p class="text-muted small">{{ $cita->motivo }}</p>
                    </div>
                    <div class="mb-0">
                        <strong>Estado:</strong><br>
                        <span class="badge bg-{{ $cita->estado == 'aprobada' ? 'success' : ($cita->estado == 'pendiente' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($cita->estado) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Reprogramación -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Nueva Programación
                    </h6>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Importante:</strong> Al reprogramar su cita, el estado cambiará a "Pendiente" y deberá esperar aprobación.
                    </div>

                    <form action="{{ route('paciente.citas.reprogramar.update', $cita->id) }}" method="POST" id="formReprogramar">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nueva Fecha *</label>
                                <input type="date" name="fecha" id="fecha" class="form-control" 
                                       min="{{ date('Y-m-d') }}" 
                                       value="{{ old('fecha', $cita->fecha) }}" required>
                                <small class="form-text text-muted">
                                    Fecha actual: {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                                </small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nueva Hora *</label>
                                <select name="hora" id="hora" class="form-select" required disabled>
                                    <option value="">Seleccione fecha primero</option>
                                </select>
                                <input type="hidden" name="doctor_schedule_id" id="doctor_schedule_id">
                                <div id="horaInfo" class="mt-2" style="display: none;">
                                    <small class="text-success">
                                        <i class="fas fa-check-circle"></i> Horario disponible
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Razón para Reprogramar *</label>
                            <textarea name="comentarios" id="comentarios" class="form-control" rows="4" 
                                      placeholder="Explique brevemente por qué necesita reprogramar su cita..." 
                                      maxlength="500" required>{{ old('comentarios') }}</textarea>
                            <div class="form-text">
                                <span id="comentariosCount">0</span>/500 caracteres
                            </div>
                        </div>                        <div class="mb-3">
                            <label class="form-label">Motivo de Consulta *</label>
                            <textarea name="motivo" id="motivo" class="form-control" rows="3" 
                                      placeholder="Describa el motivo de su consulta..." 
                                      maxlength="500" required>{{ old('motivo', $cita->motivo) }}</textarea>
                            <div class="form-text">
                                <span id="motivoCount">{{ strlen($cita->motivo) }}</span>/500 caracteres
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Puede mantener el motivo actual o modificarlo según sea necesario.
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('paciente.citas.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-secondary" id="btnSubmit" disabled>
                                <i class="fas fa-calendar-times me-1"></i> Completar Información
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let fechaInput = document.getElementById('fecha');
    let horaSelect = document.getElementById('hora');
    let comentariosTextarea = document.getElementById('comentarios');
    let motivoTextarea = document.getElementById('motivo');
    let btnSubmit = document.getElementById('btnSubmit');

    // Información de la cita original
    const citaOriginal = {
        medicoId: '{{ $cita->medico_id }}',
        clinicaId: '{{ $cita->clinica_id }}',
        fecha: '{{ $cita->fecha }}',
        hora: '{{ $cita->hora }}'
    };

    // Contador de caracteres
    comentariosTextarea.addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('comentariosCount').textContent = count;
        if (count > 480) {
            document.getElementById('comentariosCount').classList.add('text-warning');
        } else {
            document.getElementById('comentariosCount').classList.remove('text-warning');
        }
        validarFormulario();
    });

    motivoTextarea.addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('motivoCount').textContent = count;
        if (count > 480) {
            document.getElementById('motivoCount').classList.add('text-warning');
        } else {
            document.getElementById('motivoCount').classList.remove('text-warning');
        }
        validarFormulario();
    });

    // Cargar horarios cuando se seleccione fecha
    function cargarHorarios() {
        const medicoId = citaOriginal.medicoId;
        const fecha = fechaInput.value;
        const clinicaId = citaOriginal.clinicaId;
        
        if (medicoId && fecha && clinicaId && clinicaId !== 'null') {
            fetch(`/paciente/citas/horarios-disponibles?medico_id=${medicoId}&fecha=${fecha}&clinica_id=${clinicaId}`)
                .then(response => response.json())
                .then(data => {
                    horaSelect.innerHTML = '<option value="">Seleccionar nueva hora</option>';
                    
                    if (data.length === 0) {
                        horaSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                        horaSelect.disabled = true;
                    } else {
                        data.forEach(horario => {
                            const option = document.createElement('option');
                            option.value = horario.hora;
                            option.textContent = `${horario.hora}`;
                            option.dataset.scheduleId = horario.schedule_id;
                            
                            // Marcar hora actual si es la misma fecha
                            if (horario.hora === citaOriginal.hora && fecha === citaOriginal.fecha) {
                                option.textContent += ' (Hora actual)';
                                option.classList.add('text-muted');
                            }
                            
                            horaSelect.appendChild(option);
                        });
                        horaSelect.disabled = false;
                        
                        // Si es la misma fecha, preseleccionar la hora actual
                        if (fecha === citaOriginal.fecha) {
                            horaSelect.value = citaOriginal.hora;
                            const selectedOption = horaSelect.options[horaSelect.selectedIndex];
                            if (selectedOption && selectedOption.dataset.scheduleId) {
                                document.getElementById('doctor_schedule_id').value = selectedOption.dataset.scheduleId;
                                document.getElementById('horaInfo').style.display = 'block';
                            }
                        }
                    }
                    validarFormulario();
                })
                .catch(error => {
                    console.error('Error:', error);
                    horaSelect.innerHTML = '<option value="">Error al cargar horarios</option>';
                    horaSelect.disabled = true;
                });
        }
    }

    fechaInput.addEventListener('change', cargarHorarios);

    // Establecer schedule_id cuando se seleccione hora
    horaSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value && selectedOption.dataset.scheduleId) {
            document.getElementById('doctor_schedule_id').value = selectedOption.dataset.scheduleId;
            document.getElementById('horaInfo').style.display = 'block';
        } else {
            document.getElementById('doctor_schedule_id').value = '';
            document.getElementById('horaInfo').style.display = 'none';
        }
        validarFormulario();
    });

    // Validar formulario completo
    function validarFormulario() {
        const fecha = fechaInput.value;
        const hora = horaSelect.value;
        const comentarios = comentariosTextarea.value.trim();
        const motivo = motivoTextarea.value.trim();

        // Verificar que haya cambios
        const fechaCambiada = fecha !== citaOriginal.fecha;
        const horaCambiada = hora !== citaOriginal.hora;
        const huboChangios = fechaCambiada || horaCambiada;

        console.log('Validando formulario:', {
            fecha: fecha,
            hora: hora,
            comentarios: comentarios.length,
            motivo: motivo.length,
            huboChangios: huboChangios
        });

        if (huboChangios && fecha && hora && comentarios.length >= 10 && motivo.length >= 5) {
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('btn-secondary');
            btnSubmit.classList.add('btn-warning');
            btnSubmit.innerHTML = '<i class="fas fa-calendar-check me-1"></i> Reprogramar Cita';
            console.log('Formulario válido - botón habilitado');
        } else {
            btnSubmit.disabled = true;
            btnSubmit.classList.remove('btn-warning');
            btnSubmit.classList.add('btn-secondary');
            btnSubmit.innerHTML = '<i class="fas fa-calendar-times me-1"></i> Completar Información';
            console.log('Formulario inválido - botón deshabilitado');
        }
    }

    // Agregar listeners para validación
    [fechaInput, horaSelect, comentariosTextarea, motivoTextarea].forEach(element => {
        element.addEventListener('change', validarFormulario);
        element.addEventListener('input', validarFormulario);
    });

    // Cargar horarios iniciales y validación inicial
    cargarHorarios();
    validarFormulario();
});
</script>
@endpush

@section('styles')
<style>
    .card {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .form-label {
        font-weight: 600;
        color: #495057;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .text-warning {
        color: #fd7e14 !important;
    }

    .badge {
        font-size: 0.75em;
    }

    .alert {
        border-radius: 8px;
    }

    .form-check-label {
        font-size: 0.9em;
    }

    .border-warning {
        border: 2px solid #ffc107 !important;
        border-radius: 8px;
        padding: 15px;
        background-color: #fff8e1;
    }
</style>
@endsection
