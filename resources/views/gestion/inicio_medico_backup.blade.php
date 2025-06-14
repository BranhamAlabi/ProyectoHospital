@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmedico')

<div class="container-fluid mt-4">
    <!-- Header con información del médico -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h2 class="mb-0">Bienvenido, Dr. {{ $medico->usuario->nombre }}</h2>
                    <p class="mb-0">
                        Especialidades: 
                        @foreach($medico->especialidades as $especialidad)
                            <span class="badge bg-light text-dark me-1">{{ $especialidad->especialidad }}</span>
                        @endforeach
                    </p>
                    <p class="mb-0">
                        Clínicas: 
                        @foreach($medico->clinicas as $clinica)
                            <span class="badge bg-secondary me-1">{{ $clinica->nombre }}</span>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Notificaciones prioritarias -->
    @if($notificaciones->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-bell"></i> Notificaciones Prioritarias</h5>
                </div>
                <div class="card-body">
                    @foreach($notificaciones as $notificacion)
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong>{{ $notificacion->titulo }}</strong><br>
                        {{ $notificacion->mensaje }}
                        <small class="text-muted d-block">{{ $notificacion->created_at->diffForHumans() }}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">        <!-- Calendario de citas -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Gestión de Citas</h5>
                </div>                <div class="card-body">
                    <!-- Filtros mejorados -->
                    <form method="GET" action="{{ route('medico.inicio') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-filter"></i> Estado de citas
                                </label>
                                <select name="estado" class="form-select">
                                    <option value="todas" {{ $filtroEstado == 'todas' ? 'selected' : '' }}>Todas las citas</option>
                                    <option value="aprobada" {{ $filtroEstado == 'aprobada' ? 'selected' : '' }}>Aprobadas</option>
                                    <option value="pendiente" {{ $filtroEstado == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                                    <option value="cancelada" {{ $filtroEstado == 'cancelada' ? 'selected' : '' }}>Canceladas</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-calendar"></i> Período
                                </label>
                                <select name="fecha" class="form-select">
                                    <option value="proximas" {{ $filtroFecha == 'proximas' ? 'selected' : '' }}>Próximas citas</option>
                                    <option value="hoy" {{ $filtroFecha == 'hoy' ? 'selected' : '' }}>Solo hoy</option>
                                    <option value="semana" {{ $filtroFecha == 'semana' ? 'selected' : '' }}>Esta semana</option>
                                    <option value="pasadas" {{ $filtroFecha == 'pasadas' ? 'selected' : '' }}>Citas pasadas</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Filtrar
                                    </button>
                                    <a href="{{ route('medico.inicio') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-refresh"></i> Limpiar
                                    </a>
                                </div>
                            </div>
                        </div>                    </form>

                    <!-- Resumen de citas encontradas -->
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle"></i> 
                        Mostrando <strong>{{ $citas->count() }}</strong> citas
                        @if($filtroEstado !== 'todas')
                            con estado <strong>{{ $filtroEstado }}</strong>
                        @endif
                        @if($filtroFecha !== 'proximas')
                            para <strong>
                                @switch($filtroFecha)
                                    @case('hoy') hoy @break
                                    @case('semana') esta semana @break
                                    @case('pasadas') fechas pasadas @break
                                @endswitch
                            </strong>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" id="tabla-citas">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-calendar"></i> Fecha</th>
                                    <th><i class="fas fa-clock"></i> Hora</th>
                                    <th><i class="fas fa-user"></i> Paciente</th>
                                    <th><i class="fas fa-notes-medical"></i> Motivo</th>
                                    <th><i class="fas fa-flag"></i> Estado</th>
                                    <th><i class="fas fa-cogs"></i> Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($citas as $cita)                                <tr class="
                                    @if($cita->fecha == now()->toDateString()) table-warning
                                    @elseif($cita->fecha < now()->toDateString()) table-light
                                    @endif
                                ">
                                    <td>
                                        {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                                        @if($cita->fecha == now()->toDateString())
                                            <span class="badge bg-warning text-dark ms-1">HOY</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</td>
                                    <td>
                                        <strong>{{ $cita->paciente->nombre }}</strong>
                                        <br><small class="text-muted">{{ $cita->paciente->correo }}</small>
                                    </td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $cita->motivo }}">
                                            {{ $cita->motivo }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $cita->estado == 'aprobada' ? 'success' : 
                                            ($cita->estado == 'pendiente' ? 'warning' : 
                                            ($cita->estado == 'cancelada' ? 'danger' : 'secondary')) 
                                        }}">
                                            {{ ucfirst($cita->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#verCitaModal{{ $cita->id }}"
                                                    title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($cita->estado !== 'cancelada')
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-warning" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#actualizarCitaModal{{ $cita->id }}"
                                                    title="Actualizar estado">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endif                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Ver Cita -->
                                <div class="modal fade" id="verCitaModal{{ $cita->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detalles de la Cita</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Paciente:</strong> {{ $cita->paciente->nombre }}</p>
                                                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</p>
                                                <p><strong>Hora:</strong> {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</p>
                                                <p><strong>Clínica:</strong> {{ $cita->clinica->nombre ?? 'N/A' }}</p>
                                                <p><strong>Motivo:</strong> {{ $cita->motivo }}</p>
                                                <p><strong>Estado:</strong> <span class="badge bg-{{ 
                                                    $cita->estado == 'aprobada' ? 'success' : 
                                                    ($cita->estado == 'pendiente' ? 'warning' : 
                                                    ($cita->estado == 'cancelada' ? 'danger' : 'secondary')) 
                                                }}">{{ ucfirst($cita->estado) }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Actualizar Cita -->
                                <div class="modal fade" id="actualizarCitaModal{{ $cita->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Actualizar Cita</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('medico.actualizarEstadoCita', $cita->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Estado</label>
                                                        <select class="form-select" name="estado">
                                                            <option value="pendiente" {{ $cita->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                            <option value="aprobada" {{ $cita->estado == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                                                            <option value="cancelada" {{ $cita->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Comentarios</label>
                                                        <textarea class="form-control" name="comentarios" rows="3">{{ $cita->comentarios }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No se encontraron citas con los filtros seleccionados</p>
                                    </td>
                                </tr>
                                @endforelse

                                <!-- Modal Actualizar Cita -->
                                <div class="modal fade" id="actualizarCitaModal{{ $cita->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Actualizar Cita</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form id="formCita{{ $cita->id }}" action="{{ route('medico.actualizarEstadoCita', $cita->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Estado</label>
                                                        <select class="form-select" name="estado" id="estado{{ $cita->id }}">
                                                            <option value="Pendiente" {{ $cita->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                            <option value="Confirmada" {{ $cita->estado == 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
                                                            <option value="Cancelada" {{ $cita->estado == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                                                            <option value="Pendiente_reprogramacion" {{ $cita->estado == 'Pendiente_reprogramacion' ? 'selected' : '' }}>Pendiente de reprogramación</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Comentarios</label>
                                                        <textarea class="form-control" name="comentarios" rows="3">{{ $cita->comentarios }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Expediente Médico -->
                                <div class="modal fade" id="expedienteMedicoModal{{ $cita->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Crear Expediente Médico</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('medico.guardarExpediente') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="cita_id" value="{{ $cita->id }}">
                                                <input type="hidden" name="paciente_id" value="{{ $cita->paciente_id }}">
                                                <input type="hidden" name="medico_id" value="{{ $cita->medico_id }}">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Notas del Expediente</label>
                                                        <textarea class="form-control" name="notas" rows="5" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Guardar Expediente</button>
                                                </div>                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel lateral -->
        <div class="col-lg-4">            <!-- Resumen rápido -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-pie"></i> Estadísticas Generales</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="text-primary">
                                <i class="fas fa-calendar-check fa-2x"></i>
                                <div class="mt-1">
                                    <strong>{{ $totalCitas }}</strong><br>
                                    <small>Total de citas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-warning">
                                <i class="fas fa-calendar-day fa-2x"></i>
                                <div class="mt-1">
                                    <strong>{{ $citasHoy ?? 0 }}</strong><br>
                                    <small>Citas hoy</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="text-success">
                                <i class="fas fa-check-circle fa-2x"></i>
                                <div class="mt-1">
                                    <strong>{{ $citasAprobadas }}</strong><br>
                                    <small>Aprobadas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-warning">
                                <i class="fas fa-clock fa-2x"></i>
                                <div class="mt-1">
                                    <strong>{{ $citasPendientes }}</strong><br>
                                    <small>Pendientes</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-danger">
                                <i class="fas fa-times-circle fa-2x"></i>
                                <div class="mt-1">
                                    <strong>{{ $citasCanceladas }}</strong><br>
                                    <small>Canceladas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Accesos rápidos -->
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-bolt"></i> Accesos Rápidos</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('medico.inicio', ['fecha' => 'hoy']) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-calendar-day"></i> Ver citas de hoy
                        </a>
                        <a href="{{ route('medico.inicio', ['estado' => 'pendiente']) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-clock"></i> Ver citas pendientes
                        </a>
                        <a href="{{ route('medico.horarios') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-calendar-alt"></i> Gestionar horarios
                        </a>
                        <a href="{{ route('medico.expedientes') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-folder-medical"></i> Ver expedientes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.filtros-container {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #dee2e6;
}

.badge {
    font-size: 0.75em;
}

#contador-resultados {
    font-style: italic;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.btn-sm {
    font-size: 0.8rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[id^="formCita"]');
    
    forms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const citaId = form.id.replace('formCita', '');
            const formData = new FormData(form);
            const estado = formData.get('estado');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                  const result = await response.json();
                
                if (response.ok && result.success) {
                    // Cerrar el modal de actualización
                    const actualizarModal = bootstrap.Modal.getInstance(document.getElementById('actualizarCitaModal' + citaId));
                    if (actualizarModal) {
                        actualizarModal.hide();
                    }
                    
                    // Mostrar mensaje de éxito
                    alert(result.message || 'Cita actualizada correctamente');
                    
                    // Si se debe mostrar el modal de expediente
                    if (result.mostrar_expediente) {
                        setTimeout(() => {
                            const expedienteModal = new bootstrap.Modal(document.getElementById('expedienteMedicoModal' + citaId));
                            expedienteModal.show();
                        }, 500);
                    } else {
                        // Solo recargar si no se va a mostrar el modal de expediente
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                } else {
                    throw new Error(result.message || 'Error al actualizar la cita');
                }
            } catch (error) {
                console.error('Error:', error);
                alert(error.message || 'Error al actualizar la cita');
            }
        });
    });    // Mostrar modal de expediente si viene de redirección
    @if(session()->has('mostrar_expediente') && session()->has('cita_id'))
        const expedienteModal = new bootstrap.Modal(document.getElementById('expedienteMedicoModal{{ session('cita_id') }}'));
        expedienteModal.show();
    @endif
});

// Funciones para filtros de citas
function aplicarFiltros() {
    const filtroPaciente = document.getElementById('filtro-paciente').value.toLowerCase();
    const filtroEstado = document.getElementById('filtro-estado').value;
    const filtroFecha = document.getElementById('filtro-fecha').value;
    
    const tabla = document.getElementById('tabla-citas');
    const filas = tabla.querySelectorAll('tbody tr');
    
    filas.forEach(fila => {
        // Obtener datos de la fila (excluyendo modales)
        if (fila.style.display === 'none' || fila.querySelector('.modal')) {
            return; // Saltar modales y filas ya ocultas
        }
        
        const celdas = fila.querySelectorAll('td');
        if (celdas.length < 5) return; // Saltar si no tiene suficientes celdas
        
        const fechaCita = celdas[0].textContent.trim();
        const paciente = celdas[2].textContent.toLowerCase().trim();
        const estadoBadge = celdas[4].querySelector('.badge');
        const estado = estadoBadge ? estadoBadge.textContent.trim() : '';
        
        let mostrar = true;
        
        // Filtro por paciente
        if (filtroPaciente && !paciente.includes(filtroPaciente)) {
            mostrar = false;
        }
        
        // Filtro por estado
        if (filtroEstado && estado !== filtroEstado) {
            mostrar = false;
        }
        
        // Filtro por fecha
        if (filtroFecha) {
            const fechaFiltro = new Date(filtroFecha);
            const fechaCitaParts = fechaCita.split('/');
            const fechaCitaDate = new Date(fechaCitaParts[2], fechaCitaParts[1] - 1, fechaCitaParts[0]);
            
            if (fechaCitaDate.toDateString() !== fechaFiltro.toDateString()) {
                mostrar = false;
            }
        }
        
        fila.style.display = mostrar ? '' : 'none';
    });
    
    // Actualizar contador de resultados
    actualizarContadorResultados();
}

function limpiarFiltros() {
    document.getElementById('filtro-paciente').value = '';
    document.getElementById('filtro-estado').value = '';
    document.getElementById('filtro-fecha').value = '';
    
    // Mostrar todas las filas
    const tabla = document.getElementById('tabla-citas');
    const filas = tabla.querySelectorAll('tbody tr');
    
    filas.forEach(fila => {
        if (!fila.querySelector('.modal')) {
            fila.style.display = '';
        }
    });
    
    actualizarContadorResultados();
}

function actualizarContadorResultados() {
    const tabla = document.getElementById('tabla-citas');
    const filasVisibles = Array.from(tabla.querySelectorAll('tbody tr')).filter(fila => 
        fila.style.display !== 'none' && !fila.querySelector('.modal')
    );
    
    // Crear o actualizar el contador si no existe
    let contador = document.getElementById('contador-resultados');
    if (!contador) {
        contador = document.createElement('div');
        contador.id = 'contador-resultados';
        contador.className = 'text-muted mb-2';
        tabla.parentNode.insertBefore(contador, tabla);
    }
    
    contador.innerHTML = `<small><i class="fas fa-info-circle"></i> Mostrando ${filasVisibles.length} cita(s)</small>`;
}

// Aplicar filtros al escribir (con debounce)
let timeoutId;
document.addEventListener('DOMContentLoaded', function() {
    const filtroPaciente = document.getElementById('filtro-paciente');
    if (filtroPaciente) {
        filtroPaciente.addEventListener('input', function() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(aplicarFiltros, 300);
        });
    }
    
    const filtroEstado = document.getElementById('filtro-estado');
    if (filtroEstado) {
        filtroEstado.addEventListener('change', aplicarFiltros);
    }
    
    const filtroFecha = document.getElementById('filtro-fecha');
    if (filtroFecha) {
        filtroFecha.addEventListener('change', aplicarFiltros);
    }
    
    // Inicializar contador
    actualizarContadorResultados();
});
</script>
@endpush
