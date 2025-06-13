@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmedico')

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-clock"></i> Configuración de Horarios</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('medico.guardarHorarios') }}" method="POST" id="formHorarios">
                @csrf
                <div id="horarios-container">
                    @if($horarios->count() > 0)
                        @foreach($horarios as $index => $horario)
                        <div class="row mb-3 horario-row">
                            <div class="col-md-3">
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
                                <input type="time" name="horarios[{{ $index }}][hora_inicio]" class="form-control" value="{{ $horario->hora_inicio }}" required>
                            </div>
                            <div class="col-md-2">
                                <input type="time" name="horarios[{{ $index }}][hora_fin]" class="form-control" value="{{ $horario->hora_fin }}" required>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text">Pacientes/hora</span>
                                    <input type="number" name="horarios[{{ $index }}][pacientes_por_hora]" class="form-control" value="{{ $horario->pacientes_por_hora }}" min="1" max="10" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarHorario(this)">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="row mb-3 horario-row">
                            <div class="col-md-3">
                                <select name="horarios[0][dia_semana]" class="form-select" required>
                                    <option value="">Seleccione día</option>
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
                                <input type="time" name="horarios[0][hora_inicio]" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <input type="time" name="horarios[0][hora_fin]" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text">Pacientes/hora</span>
                                    <input type="number" name="horarios[0][pacientes_por_hora]" class="form-control" value="1" min="1" max="10" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarHorario(this)">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-success" onclick="agregarHorario()">
                        <i class="fas fa-plus"></i> Agregar Horario
                    </button>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Notas:
                    <ul class="mb-0">
                        <li>Puede agregar múltiples horarios por día.</li>
                        <li>El número de pacientes por hora determina los intervalos disponibles para citas.</li>
                        <li>Los cambios en horarios no afectarán a las citas ya programadas.</li>
                    </ul>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Horarios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function agregarHorario() {
    const container = document.getElementById('horarios-container');
    const index = container.children.length;
    
    const template = `
        <div class="row mb-3 horario-row">
            <div class="col-md-3">
                <select name="horarios[${index}][dia_semana]" class="form-select" required>
                    <option value="">Seleccione día</option>
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
                <input type="time" name="horarios[${index}][hora_inicio]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <input type="time" name="horarios[${index}][hora_fin]" class="form-control" required>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text">Pacientes/hora</span>
                    <input type="number" name="horarios[${index}][pacientes_por_hora]" class="form-control" value="1" min="1" max="10" required>
                </div>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarHorario(this)">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', template);
}

function eliminarHorario(button) {
    const row = button.closest('.horario-row');
    if (document.querySelectorAll('.horario-row').length > 1) {
        row.remove();
        reindexarHorarios();
    } else {
        alert('Debe mantener al menos un horario.');
    }
}

function reindexarHorarios() {
    const rows = document.querySelectorAll('.horario-row');
    rows.forEach((row, index) => {
        row.querySelectorAll('[name^="horarios["]').forEach(input => {
            const fieldName = input.name.match(/\[(.*?)\]/g)[1];
            input.name = `horarios[${index}]${fieldName}`;
        });
    });
}

document.getElementById('formHorarios').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validar que no haya solapamiento de horarios
    const horarios = [];
    document.querySelectorAll('.horario-row').forEach(row => {
        const dia = row.querySelector('[name$="[dia_semana]"]').value;
        const inicio = row.querySelector('[name$="[hora_inicio]"]').value;
        const fin = row.querySelector('[name$="[hora_fin]"]').value;
        
        horarios.push({dia, inicio, fin});
    });
    
    // Verificar solapamientos
    for (let i = 0; i < horarios.length; i++) {
        for (let j = i + 1; j < horarios.length; j++) {
            if (horarios[i].dia === horarios[j].dia) {
                const inicio1 = new Date(`2000-01-01T${horarios[i].inicio}`);
                const fin1 = new Date(`2000-01-01T${horarios[i].fin}`);
                const inicio2 = new Date(`2000-01-01T${horarios[j].inicio}`);
                const fin2 = new Date(`2000-01-01T${horarios[j].fin}`);
                
                if (inicio1 < fin2 && inicio2 < fin1) {
                    alert(`Hay horarios solapados en ${horarios[i].dia}`);
                    return;
                }
            }
        }
    }
    
    this.submit();
});
</script>
@endsection
