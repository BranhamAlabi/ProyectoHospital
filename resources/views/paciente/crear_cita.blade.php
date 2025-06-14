@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navpaciente')

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-calendar-plus"></i> Agendar Nueva Cita</h5>
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

            <form action="{{ route('paciente.citas.store') }}" method="POST" id="formNuevaCita">
                @csrf
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="bg-light p-3 rounded">
                            <h6 class="text-primary"><i class="fas fa-info-circle"></i> Información Importante</h6>
                            <ul class="mb-0 small">
                                <li>Su historial de asistencia determinará el estado automático de la cita</li>
                                <li>Las citas se aprueban automáticamente si tiene más del 80% de asistencia</li>
                                <li>Asegúrese de seleccionar la fecha y hora correctas</li>
                                <li>El motivo de la consulta debe ser claro y específico</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Especialidad Médica *</label>
                        <select name="especialidad_id" id="especialidad_id" class="form-select" required>
                            <option value="">Seleccionar especialidad</option>
                            @foreach($especialidades as $especialidad)
                                <option value="{{ $especialidad->id }}">{{ $especialidad->especialidad }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Clínica *</label>
                        <select name="clinica_id" id="clinica_id" class="form-select" required>
                            <option value="">Seleccionar clínica</option>
                            @foreach($clinicas as $clinica)
                                <option value="{{ $clinica->id }}">{{ $clinica->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Médico *</label>
                        <select name="medico_id" id="medico_id" class="form-select" required disabled>
                            <option value="">Primero seleccione especialidad y clínica</option>
                        </select>
                        <div id="medicoInfo" class="mt-2" style="display: none;">
                            <small class="text-muted">
                                <i class="fas fa-user-md"></i> <span id="medicoNombre"></span><br>
                                <i class="fas fa-stethoscope"></i> <span id="medicoEspecialidades"></span>
                            </small>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha de la Cita *</label>
                        <input type="date" name="fecha" id="fecha" class="form-control" 
                               min="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hora Disponible *</label>
                        <select name="hora" id="hora" class="form-select" required disabled>
                            <option value="">Primero seleccione médico y fecha</option>
                        </select>
                        <input type="hidden" name="doctor_schedule_id" id="doctor_schedule_id">
                        <div id="horaInfo" class="mt-2" style="display: none;">
                            <small class="text-success">
                                <i class="fas fa-check-circle"></i> Horario disponible
                            </small>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Estado Automático</label>
                        <div id="estadoInfo" class="mt-2">
                            <div class="alert alert-info small">
                                <i class="fas fa-robot"></i> El estado se determinará automáticamente según su historial de asistencia
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Motivo de la Consulta *</label>
                    <textarea name="motivo" id="motivo" class="form-control" rows="4" 
                              placeholder="Describa brevemente el motivo de su consulta médica..." 
                              maxlength="500" required></textarea>
                    <div class="form-text">
                        <span id="motivoCount">0</span>/500 caracteres
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('paciente.inicio') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary" id="btnSubmit" disabled>
                        <i class="fas fa-calendar-check"></i> Agendar Cita
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let especialidadSelect = document.getElementById('especialidad_id');
let clinicaSelect = document.getElementById('clinica_id');
let medicoSelect = document.getElementById('medico_id');
let fechaInput = document.getElementById('fecha');
let horaSelect = document.getElementById('hora');
let motivoTextarea = document.getElementById('motivo');
let btnSubmit = document.getElementById('btnSubmit');

// Contador de caracteres del motivo
motivoTextarea.addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('motivoCount').textContent = count;
    if (count > 480) {
        document.getElementById('motivoCount').classList.add('text-warning');
    } else {
        document.getElementById('motivoCount').classList.remove('text-warning');
    }
});

// Cargar médicos cuando se seleccione especialidad y clínica
function cargarMedicos() {
    const especialidadId = especialidadSelect.value;
    const clinicaId = clinicaSelect.value;
    
    console.log('Cargando médicos para especialidad:', especialidadId, 'clínica:', clinicaId);
    
    // Solo requerir especialidad, clínica es opcional
    if (especialidadId) {
        const params = new URLSearchParams({
            especialidad_id: especialidadId
        });
        
        if (clinicaId) {
            params.append('clinica_id', clinicaId);
        }
        
        const url = `{{ route('paciente.citas.medicosPorEspecialidad') }}?${params.toString()}`;
        
        console.log('URL de petición:', url);
        
        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Médicos recibidos:', data); // Para debug
                
                if (data.error) {
                    throw new Error(data.error);
                }
                
                medicoSelect.innerHTML = '<option value="">Seleccionar médico</option>';
                
                if (data.length === 0) {
                    medicoSelect.innerHTML = '<option value="">No hay médicos disponibles</option>';
                    medicoSelect.disabled = true;
                } else {
                    data.forEach(medico => {
                        const option = document.createElement('option');
                        option.value = medico.id;
                        option.textContent = medico.usuario.nombre;
                        option.dataset.especialidades = medico.especialidades.map(e => e.especialidad).join(', ');
                        medicoSelect.appendChild(option);
                    });
                    medicoSelect.disabled = false;
                }
                
                // Limpiar horarios
                horaSelect.innerHTML = '<option value="">Primero seleccione médico y fecha</option>';
                horaSelect.disabled = true;
                document.getElementById('medicoInfo').style.display = 'none';
            })            .catch(error => {
                console.error('Error completo:', error);
                
                medicoSelect.innerHTML = '<option value="">Error al cargar médicos</option>';
                medicoSelect.disabled = true;
                
                // Mostrar error más detallado
                let errorMessage = 'Error al cargar médicos';
                if (error.message) {
                    errorMessage += ': ' + error.message;
                }
                
                // Mostrar en la interfaz en lugar de alert
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger mt-2';
                errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${errorMessage}`;
                
                // Remover errores anteriores
                const existingError = medicoSelect.parentNode.querySelector('.alert-danger');
                if (existingError) {
                    existingError.remove();
                }
                
                medicoSelect.parentNode.appendChild(errorDiv);
                
                // Auto-remover después de 5 segundos
                setTimeout(() => {
                    if (errorDiv.parentNode) {
                        errorDiv.remove();
                    }
                }, 5000);
            });} else {
        medicoSelect.innerHTML = '<option value="">Primero seleccione especialidad</option>';
        medicoSelect.disabled = true;
        horaSelect.innerHTML = '<option value="">Primero seleccione médico y fecha</option>';
        horaSelect.disabled = true;
        document.getElementById('medicoInfo').style.display = 'none';
    }
}

especialidadSelect.addEventListener('change', cargarMedicos);
clinicaSelect.addEventListener('change', cargarMedicos);

// Mostrar información del médico seleccionado
medicoSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    if (selectedOption.value) {
        document.getElementById('medicoNombre').textContent = selectedOption.textContent;
        document.getElementById('medicoEspecialidades').textContent = selectedOption.dataset.especialidades;
        document.getElementById('medicoInfo').style.display = 'block';
        cargarHorarios();
    } else {
        document.getElementById('medicoInfo').style.display = 'none';
        horaSelect.innerHTML = '<option value="">Primero seleccione médico y fecha</option>';
        horaSelect.disabled = true;
    }
});

// Cargar horarios cuando se seleccione médico y fecha
function cargarHorarios() {
    const medicoId = medicoSelect.value;
    const fecha = fechaInput.value;
    const clinicaId = clinicaSelect.value;
    
    if (medicoId && fecha && clinicaId) {
        fetch(`/paciente/citas/horarios-disponibles?medico_id=${medicoId}&fecha=${fecha}&clinica_id=${clinicaId}`)
            .then(response => response.json())
            .then(data => {
                horaSelect.innerHTML = '<option value="">Seleccionar hora</option>';
                
                if (data.length === 0) {
                    horaSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                    horaSelect.disabled = true;
                } else {
                    data.forEach(horario => {
                        const option = document.createElement('option');
                        option.value = horario.hora;
                        option.textContent = `${horario.hora} (${horario.disponibles}/${horario.total} disponibles)`;
                        option.dataset.scheduleId = horario.schedule_id;
                        horaSelect.appendChild(option);
                    });
                    horaSelect.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar horarios');
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
    const especialidad = especialidadSelect.value;
    const medico = medicoSelect.value;
    const fecha = fechaInput.value;
    const hora = horaSelect.value;
    const motivo = motivoTextarea.value.trim();
    
    console.log('Validando formulario:', {
        especialidad: especialidad,
        medico: medico,
        fecha: fecha,
        hora: hora,
        motivo: motivo.length
    });
      // Clínica es opcional, no la incluimos en la validación
    if (especialidad && medico && fecha && hora && motivo.length >= 5) {
        btnSubmit.disabled = false;
        btnSubmit.classList.remove('btn-secondary');
        btnSubmit.classList.add('btn-primary');
        console.log('Formulario válido - botón habilitado');
    } else {
        btnSubmit.disabled = true;
        btnSubmit.classList.remove('btn-primary');
        btnSubmit.classList.add('btn-secondary');
        console.log('Formulario inválido - botón deshabilitado');
    }
}

// Agregar listeners para validación
[especialidadSelect, clinicaSelect, medicoSelect, fechaInput, horaSelect, motivoTextarea].forEach(element => {
    element.addEventListener('change', validarFormulario);
    element.addEventListener('input', validarFormulario);
});

// Validación inicial
validarFormulario();
</script>
@endpush

@push('styles')
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

.alert-info {
    background-color: #e3f2fd;
    border-color: #2196f3;
    color: #0d47a1;
}

#medicoInfo {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    border-left: 4px solid #007bff;
}

#horaInfo {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    border-left: 4px solid #28a745;
}

.text-warning {
    color: #fd7e14 !important;
}
</style>
@endpush
