@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmedico')

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-clock"></i> Configuración de Horarios</h5>
        </div>
        <div class="card-body">            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-3 fs-4"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

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

            <form action="{{ route('medico.guardarHorarios') }}" method="POST" id="formHorarios">
                @csrf
                  <div class="row mb-4">
                    <div class="col-12">
                        <div class="bg-light p-3 rounded">
                            <h6 class="text-primary"><i class="fas fa-info-circle"></i> Instrucciones</h6>
                            <ul class="mb-0 small">
                                <li>Las horas deben ser en punto (ej: 08:00, 09:00, no 08:30)</li>
                                <li>No puede haber solapamientos de horarios en el mismo día y clínica</li>
                                <li>La hora de fin debe ser posterior a la hora de inicio</li>
                                <li>Puede configurar diferentes horarios para diferentes clínicas</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-warning border-0">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-exclamation-triangle me-3 mt-1"></i>
                                <div>
                                    <strong>Importante:</strong> Si modifica o elimina horarios que ya tienen citas aprobadas (pendientes de confirmación), 
                                    esas citas cambiarán automáticamente a estado <strong>"Pendiente de reprogramación"</strong> para que pueda reagendarlas 
                                    con los pacientes afectados.
                                </div>
                            </div>
                        </div>
                    </div>
                </div><div id="horarios-container">
                    @if($horarios->count() > 0)
                        
                        @foreach($horarios as $index => $horario)
                        <div class="row mb-3 horario-row border rounded p-3">
                            <div class="col-md-2">
                                <label class="form-label">Día de la semana</label>
                                <select name="horarios[{{ $index }}][dia_semana]" class="form-select" required>
                                    <option value="Lunes" {{ $horario->dia_semana == 'Lunes' ? 'selected' : '' }}>Lunes</option>
                                    <option value="Martes" {{ $horario->dia_semana == 'Martes' ? 'selected' : '' }}>Martes</option>
                                    <option value="Miércoles" {{ $horario->dia_semana == 'Miércoles' ? 'selected' : '' }}>Miércoles</option>
                                    <option value="Jueves" {{ $horario->dia_semana == 'Jueves' ? 'selected' : '' }}>Jueves</option>
                                    <option value="Viernes" {{ $horario->dia_semana == 'Viernes' ? 'selected' : '' }}>Viernes</option>
                                    <option value="Sábado" {{ $horario->dia_semana == 'Sábado' ? 'selected' : '' }}>Sábado</option>
                                    <option value="Domingo" {{ $horario->dia_semana == 'Domingo' ? 'selected' : '' }}>Domingo</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Clínica</label>
                                <select name="horarios[{{ $index }}][clinica_id]" class="form-select" required>
                                    @foreach($clinicas as $clinica)
                                        <option value="{{ $clinica->id }}" {{ $horario->clinica_id == $clinica->id ? 'selected' : '' }}>
                                            {{ $clinica->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>                            <div class="col-md-2">
                                <label class="form-label">Hora inicio</label>
                                <select name="horarios[{{ $index }}][hora_inicio]" class="form-select hora-select" required>
                                    @for($h = 6; $h <= 22; $h++)
                                        @php
                                            $horaFormato = sprintf('%02d:00', $h);
                                            // Obtener la hora como string y extraer solo HH:MM
                                            $horaInicioBD = $horario->hora_inicio;
                                            if ($horaInicioBD instanceof \Carbon\Carbon) {
                                                $horaInicioBD = $horaInicioBD->format('H:i');
                                            } else {
                                                // Si es string, extraer solo HH:MM
                                                $horaInicioBD = substr($horaInicioBD, 0, 5);
                                            }
                                            $isSelected = ($horaInicioBD == $horaFormato);
                                        @endphp
                                        <option value="{{ $horaFormato }}" {{ $isSelected ? 'selected' : '' }}>
                                            {{ $horaFormato }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Hora fin</label>
                                <select name="horarios[{{ $index }}][hora_fin]" class="form-select hora-select" required>
                                    @for($h = 7; $h <= 23; $h++)
                                        @php
                                            $horaFormato = sprintf('%02d:00', $h);
                                            // Obtener la hora como string y extraer solo HH:MM
                                            $horaFinBD = $horario->hora_fin;
                                            if ($horaFinBD instanceof \Carbon\Carbon) {
                                                $horaFinBD = $horaFinBD->format('H:i');
                                            } else {
                                                // Si es string, extraer solo HH:MM
                                                $horaFinBD = substr($horaFinBD, 0, 5);
                                            }
                                            $isSelected = ($horaFinBD == $horaFormato);
                                        @endphp
                                        <option value="{{ $horaFormato }}" {{ $isSelected ? 'selected' : '' }}>
                                            {{ $horaFormato }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Max. pacientes/hora</label>
                                <input type="number" name="horarios[{{ $index }}][pacientes_por_hora]" 
                                       class="form-control" value="{{ $horario->pacientes_por_hora }}" 
                                       min="1" max="10" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Acciones</label>
                                <button type="button" class="btn btn-danger btn-sm w-100" onclick="eliminarHorario(this)">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="row mb-3 horario-row border rounded p-3">
                            <div class="col-md-2">
                                <label class="form-label">Día de la semana</label>
                                <select name="horarios[0][dia_semana]" class="form-select" required>
                                    <option value="">Seleccionar día</option>
                                    <option value="Lunes">Lunes</option>
                                    <option value="Martes">Martes</option>
                                    <option value="Miércoles">Miércoles</option>
                                    <option value="Jueves">Jueves</option>
                                    <option value="Viernes">Viernes</option>
                                    <option value="Sábado">Sábado</option>
                                    <option value="Domingo">Domingo</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Clínica</label>
                                <select name="horarios[0][clinica_id]" class="form-select" required>
                                    <option value="">Seleccionar clínica</option>
                                    @foreach($clinicas as $clinica)
                                        <option value="{{ $clinica->id }}">{{ $clinica->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Hora inicio</label>
                                <select name="horarios[0][hora_inicio]" class="form-select hora-select" required>
                                    <option value="">Seleccionar hora</option>
                                    @for($h = 6; $h <= 22; $h++)
                                        <option value="{{ sprintf('%02d:00', $h) }}">{{ sprintf('%02d:00', $h) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Hora fin</label>
                                <select name="horarios[0][hora_fin]" class="form-select hora-select" required>
                                    <option value="">Seleccionar hora</option>
                                    @for($h = 7; $h <= 23; $h++)
                                        <option value="{{ sprintf('%02d:00', $h) }}">{{ sprintf('%02d:00', $h) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Max. pacientes/hora</label>
                                <input type="number" name="horarios[0][pacientes_por_hora]" 
                                       class="form-control" value="1" min="1" max="10" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Acciones</label>
                                <button type="button" class="btn btn-danger btn-sm w-100" onclick="eliminarHorario(this)">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <button type="button" class="btn btn-success" onclick="agregarHorario()">
                            <i class="fas fa-plus"></i> Agregar Horario
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Horarios
                        </button>
                        
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let contadorHorarios = {{ $horarios->count() > 0 ? $horarios->count() : 1 }};

function agregarHorario() {
    const container = document.getElementById('horarios-container');
    const nuevoHorario = `
        <div class="row mb-3 horario-row border rounded p-3">
            <div class="col-md-2">
                <label class="form-label">Día de la semana</label>
                <select name="horarios[${contadorHorarios}][dia_semana]" class="form-select" required>
                    <option value="">Seleccionar día</option>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Clínica</label>
                <select name="horarios[${contadorHorarios}][clinica_id]" class="form-select" required>
                    <option value="">Seleccionar clínica</option>
                    @foreach($clinicas as $clinica)
                        <option value="{{ $clinica->id }}">{{ $clinica->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Hora inicio</label>
                <select name="horarios[${contadorHorarios}][hora_inicio]" class="form-select hora-select" required>
                    <option value="">Seleccionar hora</option>
                    @for($h = 6; $h <= 22; $h++)
                        <option value="{{ sprintf('%02d:00', $h) }}">{{ sprintf('%02d:00', $h) }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Hora fin</label>
                <select name="horarios[${contadorHorarios}][hora_fin]" class="form-select hora-select" required>
                    <option value="">Seleccionar hora</option>
                    @for($h = 7; $h <= 23; $h++)
                        <option value="{{ sprintf('%02d:00', $h) }}">{{ sprintf('%02d:00', $h) }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Max. pacientes/hora</label>
                <input type="number" name="horarios[${contadorHorarios}][pacientes_por_hora]" 
                       class="form-control" value="1" min="1" max="10" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Acciones</label>
                <button type="button" class="btn btn-danger btn-sm w-100" onclick="eliminarHorario(this)">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>`;
    
    container.insertAdjacentHTML('beforeend', nuevoHorario);
    contadorHorarios++;
}

function eliminarHorario(button) {
    const horarioRow = button.closest('.horario-row');
    horarioRow.remove();
    
    // Si no quedan horarios, agregar uno por defecto
    const container = document.getElementById('horarios-container');
    if (container.children.length === 0) {
        agregarHorario();
    }
}

function validarHorarios() {
    const horarios = [];
    const filas = document.querySelectorAll('.horario-row');
    
    filas.forEach((fila, index) => {
        const dia = fila.querySelector('select[name*="[dia_semana]"]').value;
        const clinica = fila.querySelector('select[name*="[clinica_id]"]').value;
        const horaInicio = fila.querySelector('select[name*="[hora_inicio]"]').value;
        const horaFin = fila.querySelector('select[name*="[hora_fin]"]').value;
        const pacientes = fila.querySelector('input[name*="[pacientes_por_hora]"]').value;
        
        if (dia && clinica && horaInicio && horaFin && pacientes) {
            horarios.push({
                index: index,
                dia: dia,
                clinica: clinica,
                horaInicio: horaInicio,
                horaFin: horaFin,
                pacientes: parseInt(pacientes)
            });
        }
    });
    
    // Validar solapamientos
    const errores = [];
    
    for (let i = 0; i < horarios.length; i++) {
        for (let j = i + 1; j < horarios.length; j++) {
            const h1 = horarios[i];
            const h2 = horarios[j];
            
            // Solo validar si es el mismo día y la misma clínica
            if (h1.dia === h2.dia && h1.clinica === h2.clinica) {
                const inicio1 = new Date('2000-01-01 ' + h1.horaInicio);
                const fin1 = new Date('2000-01-01 ' + h1.horaFin);
                const inicio2 = new Date('2000-01-01 ' + h2.horaInicio);
                const fin2 = new Date('2000-01-01 ' + h2.horaFin);
                
                // Verificar solapamiento
                if ((inicio1 < fin2 && fin1 > inicio2)) {
                    errores.push(`Solapamiento detectado: ${h1.dia} de ${h1.horaInicio} a ${h1.horaFin} con ${h2.horaInicio} a ${h2.horaFin} en la misma clínica`);
                }
            }
        }
        
        // Validar que hora fin sea mayor que hora inicio
        const inicio = new Date('2000-01-01 ' + h1.horaInicio);
        const fin = new Date('2000-01-01 ' + h1.horaFin);
        
        if (fin <= inicio) {
            errores.push(`La hora de fin debe ser posterior a la hora de inicio en ${h1.dia}`);
        }
    }
    
    if (errores.length > 0) {
        alert('Errores encontrados:\n\n' + errores.join('\n'));
        return false;
    } else {
        alert('✓ Todos los horarios son válidos');
        return true;
    }
}

// Validar antes de enviar el formulario
document.getElementById('formHorarios').addEventListener('submit', function(e) {
    if (!validarHorarios()) {
        e.preventDefault();
    }
});
</script>
@endpush

@push('styles')
<style>
.horario-row {
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}

.horario-row:hover {
    background-color: #e9ecef;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.9em;
}

.btn-danger {
    transition: all 0.3s ease;
}

.btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
}

.btn-success {
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(25, 135, 84, 0.3);
}

.alert {
    border: none;
    border-radius: 8px;
}

.bg-light {
    border-left: 4px solid #007bff;
}
</style>
@endpush
