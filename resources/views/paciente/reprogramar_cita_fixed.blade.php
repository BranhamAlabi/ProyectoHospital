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
                                <select name="hora" id="hora" class="form-select" required>
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
                        </div>

                        <div class="mb-3">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>¿Desea mantener el mismo motivo de consulta?</strong>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="mantenerMotivo" checked>
                                    <label class="form-check-label" for="mantenerMotivo">
                                        Mantener motivo actual: "{{ Str::limit($cita->motivo, 50) }}"
                                    </label>
                                </div>
                            </div>
                            
                            <div id="nuevoMotivoDiv" style="display: none;">
                                <label class="form-label">Nuevo Motivo de Consulta</label>
                                <textarea name="motivo" id="motivo" class="form-control" rows="3" 
                                          placeholder="Describa el nuevo motivo de su consulta..." 
                                          maxlength="500">{{ old('motivo', $cita->motivo) }}</textarea>
                                <div class="form-text">
                                    <span id="motivoCount">{{ strlen($cita->motivo) }}</span>/500 caracteres
                                </div>
                            </div>
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

@section('scripts')
<script>
    // Variables globales
    let fechaInput, horaSelect, comentariosTextarea, btnSubmit, mantenerMotivoCheck, nuevoMotivoDiv, motivoTextarea;
    
    // Información de la cita original
    const citaOriginal = {
        medicoId: {{ $cita->medico_id }},
        clinicaId: {{ $cita->clinica_id ?? 'null' }},
        fecha: '{{ $cita->fecha }}',
        hora: '{{ $cita->hora }}'
    };

    // Función para cargar horarios disponibles
    function cargarHorarios() {
        const fecha = fechaInput.value;
        
        console.log('Cargando horarios para fecha:', fecha);
        
        if (!fecha) {
            horaSelect.innerHTML = '<option value="">Seleccione fecha primero</option>';
            horaSelect.disabled = true;
            return;
        }

        // Mostrar loading
        horaSelect.innerHTML = '<option value="">Cargando horarios...</option>';
        horaSelect.disabled = true;

        // Construir URL
        let url = `{{ route('paciente.citas.horariosDisponibles') }}?medico_id=${citaOriginal.medicoId}&fecha=${fecha}`;
        if (citaOriginal.clinicaId && citaOriginal.clinicaId !== null) {
            url += `&clinica_id=${citaOriginal.clinicaId}`;
        }

        console.log('URL:', url);

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Respuesta:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Horarios recibidos:', data);
            
            horaSelect.innerHTML = '<option value="">Seleccionar nueva hora</option>';
            
            if (!data || data.length === 0) {
                horaSelect.innerHTML = '<option value="">No hay horarios disponibles para esta fecha</option>';
                horaSelect.disabled = true;
            } else {
                data.forEach(horario => {
                    const option = document.createElement('option');
                    option.value = horario.hora;
                    option.textContent = horario.hora;
                    
                    if (horario.schedule_id) {
                        option.dataset.scheduleId = horario.schedule_id;
                    }
                    
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
                    }
                }
            }
            
            validarFormulario();
        })
        .catch(error => {
            console.error('Error al cargar horarios:', error);
            horaSelect.innerHTML = '<option value="">Error al cargar horarios</option>';
            horaSelect.disabled = true;
        });
    }

    // Función para validar formulario
    function validarFormulario() {
        const fecha = fechaInput.value;
        const hora = horaSelect.value;
        const comentarios = comentariosTextarea.value.trim();
        const mantenerMotivo = mantenerMotivoCheck.checked;
        const nuevoMotivo = motivoTextarea ? motivoTextarea.value.trim() : '';

        // Verificar que haya cambios
        const fechaCambiada = fecha !== citaOriginal.fecha;
        const horaCambiada = hora !== citaOriginal.hora;
        const huboChangios = fechaCambiada || horaCambiada;

        // Validar campos requeridos
        let motivoValido = true;
        if (!mantenerMotivo) {
            motivoValido = nuevoMotivo.length >= 5;
        }

        const formularioValido = huboChangios && 
                                fecha && 
                                hora && 
                                comentarios.length >= 10 && 
                                motivoValido;

        console.log('Validación:', {
            formularioValido,
            huboChangios,
            fechaCambiada,
            horaCambiada,
            comentariosLength: comentarios.length,
            motivoValido
        });

        if (formularioValido) {
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('btn-secondary');
            btnSubmit.classList.add('btn-warning');
            btnSubmit.innerHTML = '<i class="fas fa-calendar-check me-1"></i> Reprogramar Cita';
        } else {
            btnSubmit.disabled = true;
            btnSubmit.classList.remove('btn-warning');
            btnSubmit.classList.add('btn-secondary');
            btnSubmit.innerHTML = '<i class="fas fa-calendar-times me-1"></i> Completar Información';
        }
    }

    // Inicialización cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar variables
        fechaInput = document.getElementById('fecha');
        horaSelect = document.getElementById('hora');
        comentariosTextarea = document.getElementById('comentarios');
        btnSubmit = document.getElementById('btnSubmit');
        mantenerMotivoCheck = document.getElementById('mantenerMotivo');
        nuevoMotivoDiv = document.getElementById('nuevoMotivoDiv');
        motivoTextarea = document.getElementById('motivo');

        console.log('DOM cargado, elementos encontrados:', {
            fechaInput: !!fechaInput,
            horaSelect: !!horaSelect,
            mantenerMotivoCheck: !!mantenerMotivoCheck,
            motivoTextarea: !!motivoTextarea
        });

        // Event listeners
        fechaInput.addEventListener('change', function() {
            cargarHorarios();
        });

        horaSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value && selectedOption.dataset.scheduleId) {
                document.getElementById('doctor_schedule_id').value = selectedOption.dataset.scheduleId;
            } else {
                document.getElementById('doctor_schedule_id').value = '';
            }
            validarFormulario();
        });

        comentariosTextarea.addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('comentariosCount').textContent = count;
            validarFormulario();
        });

        mantenerMotivoCheck.addEventListener('change', function() {
            if (this.checked) {
                nuevoMotivoDiv.style.display = 'none';
                motivoTextarea.required = false;
            } else {
                nuevoMotivoDiv.style.display = 'block';
                motivoTextarea.required = true;
                motivoTextarea.focus();
            }
            validarFormulario();
        });

        if (motivoTextarea) {
            motivoTextarea.addEventListener('input', function() {
                const count = this.value.length;
                document.getElementById('motivoCount').textContent = count;
                validarFormulario();
            });
        }

        // Manejar envío del formulario
        document.getElementById('formReprogramar').addEventListener('submit', function(e) {
            if (mantenerMotivoCheck.checked) {
                motivoTextarea.removeAttribute('name');
            } else {
                motivoTextarea.setAttribute('name', 'motivo');
            }
        });

        // Cargar horarios iniciales
        cargarHorarios();
        
        // Inicializar contadores
        comentariosTextarea.dispatchEvent(new Event('input'));
        if (motivoTextarea && !mantenerMotivoCheck.checked) {
            motivoTextarea.dispatchEvent(new Event('input'));
        }
        
        // Validación inicial
        validarFormulario();
    });
</script>
@endsection

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
