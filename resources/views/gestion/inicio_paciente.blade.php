@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navpaciente')

<div class="container mt-4">
    <h2>Panel Principal del Paciente</h2>    <!-- Resumen de Citas -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Resumen de Citas</h5>
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <label for="filtroFecha" class="form-label mb-0">Fecha:</label>
                    <select id="filtroFecha" class="form-select form-select-sm d-inline-block w-auto">
                        <option value="proximas" selected>Próximas</option>
                        <option value="pasadas">Pasadas</option>
                        <option value="todas">Todas</option>
                    </select>
                </div>

                <div class="me-3">
                    <label for="filtroEstado" class="form-label mb-0">Estado:</label>
                    <select id="filtroEstado" class="form-select form-select-sm d-inline-block w-auto">
                        <option value="todos" selected>Todos</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobada">Aprobada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>

                <a href="{{ route('paciente.citas.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Agendar Nueva Cita
                </a>
            </div>
        </div>
        
        <!-- Estadísticas rápidas -->
        <div class="card-body pb-2">
            <div class="row text-center mb-3">
                <div class="col-md-3">
                    <div class="bg-primary text-white p-2 rounded">
                        <i class="fas fa-calendar-check"></i>
                        <h6 class="mb-0">{{ $citasProximas->count() }}</h6>
                        <small>Próximas</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-success text-white p-2 rounded">
                        <i class="fas fa-check-circle"></i>
                        <h6 class="mb-0">{{ $citasAprobadas ?? 0 }}</h6>
                        <small>Aprobadas</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-warning text-white p-2 rounded">
                        <i class="fas fa-clock"></i>
                        <h6 class="mb-0">{{ $citasPendientes ?? 0 }}</h6>
                        <small>Pendientes</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-info text-white p-2 rounded">
                        <i class="fas fa-percentage"></i>
                        <h6 class="mb-0">{{ $porcentajeAsistencia ?? 100 }}%</h6>
                        <small>Asistencia</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0" id="tablaCitas">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="fas fa-calendar"></i> Fecha</th>
                            <th><i class="fas fa-clock"></i> Hora</th>
                            <th><i class="fas fa-user-md"></i> Médico</th>
                            <th><i class="fas fa-stethoscope"></i> Especialidad</th>
                            <th><i class="fas fa-hospital"></i> Clínica</th>
                            <th><i class="fas fa-info-circle"></i> Estado</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>                    @foreach ($citasProximas as $cita)
                    <tr data-fecha="{{ $cita->fecha }}" data-estado="{{ strtolower($cita->estado) }}" class="cita-row">
                        <td>
                            <span class="fw-bold">{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</span><br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($cita->fecha)->locale('es')->isoFormat('dddd') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $cita->hora }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle text-primary me-2"></i>
                                <div>
                                    <div class="fw-bold">{{ $cita->medico->usuario->nombre ?? 'N/A' }}</div>
                                    <small class="text-muted">Dr./Dra.</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($cita->medico && $cita->medico->especialidades)
                                <span class="badge bg-info">{{ $cita->medico->especialidades->first()->especialidad ?? 'N/A' }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($cita->clinica)
                                <i class="fas fa-map-marker-alt text-success me-1"></i>{{ $cita->clinica->nombre }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeClass = match($cita->estado) {
                                    'aprobada' => 'bg-success',
                                    'pendiente' => 'bg-warning text-dark',
                                    'cancelada' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                                $icon = match($cita->estado) {
                                    'aprobada' => 'fas fa-check-circle',
                                    'pendiente' => 'fas fa-clock',
                                    'cancelada' => 'fas fa-times-circle',
                                    default => 'fas fa-question-circle'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                <i class="{{ $icon }}"></i> {{ ucfirst($cita->estado) }}
                            </span>
                        </td>
                        <td>
                            @if($cita->fecha >= now()->toDateString() && $cita->estado !== 'cancelada')
                                <button class="btn btn-warning btn-sm me-1" onclick="reprogramarCita({{ $cita->id }})" title="Reprogramar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="cancelarCita({{ $cita->id }})" title="Cancelar">
                                    <i class="fas fa-times"></i>
                                </button>
                            @else
                                <span class="text-muted">Sin acciones</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @foreach ($citasPasadas as $cita)
                    <tr data-fecha="{{ $cita->fecha }}" data-estado="{{ strtolower($cita->estado) }}" class="cita-row">
                        <td>
                            <span class="fw-bold">{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</span><br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($cita->fecha)->locale('es')->isoFormat('dddd') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $cita->hora }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle text-secondary me-2"></i>
                                <div>
                                    <div>{{ $cita->medico->usuario->nombre ?? 'N/A' }}</div>
                                    <small class="text-muted">Dr./Dra.</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($cita->medico && $cita->medico->especialidades)
                                <span class="badge bg-secondary">{{ $cita->medico->especialidades->first()->especialidad ?? 'N/A' }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($cita->clinica)
                                <i class="fas fa-map-marker-alt text-muted me-1"></i>{{ $cita->clinica->nombre }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeClass = match($cita->estado) {
                                    'aprobada' => 'bg-success',
                                    'pendiente' => 'bg-warning text-dark',
                                    'cancelada' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                                $icon = match($cita->estado) {
                                    'aprobada' => 'fas fa-check-circle',
                                    'pendiente' => 'fas fa-clock',
                                    'cancelada' => 'fas fa-times-circle',
                                    default => 'fas fa-question-circle'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                <i class="{{ $icon }}"></i> {{ ucfirst($cita->estado) }}
                            </span>
                            @if($cita->asistio === true)
                                <br><small class="text-success"><i class="fas fa-check"></i> Asistió</small>
                            @elseif($cita->asistio === false)
                                <br><small class="text-danger"><i class="fas fa-times"></i> No asistió</small>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted">Cita pasada</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>




<script>
    // JavaScript para filtrar citas por fecha y estado
    document.addEventListener('DOMContentLoaded', function() {
        const filtroFecha = document.getElementById('filtroFecha');
        const filtroEstado = document.getElementById('filtroEstado');
        const tablaCitas = document.getElementById('tablaCitas').getElementsByTagName('tbody')[0];

        function filtrarCitas() {
            const fechaValor = filtroFecha.value;
            const estadoValor = filtroEstado.value;
            const hoy = new Date().toISOString().slice(0,10);

            for (let row of tablaCitas.rows) {
                const fecha = row.getAttribute('data-fecha');
                const estado = row.getAttribute('data-estado');

                let mostrar = true;

                // Filtro por fecha
                if (fechaValor === 'proximas' && fecha < hoy) {
                    mostrar = false;
                } else if (fechaValor === 'pasadas' && fecha >= hoy) {
                    mostrar = false;
                }

                // Filtro por estado
                if (estadoValor !== 'todos' && estado !== estadoValor) {
                    mostrar = false;
                }

                row.style.display = mostrar ? '' : 'none';
            }
        }

        filtroFecha.addEventListener('change', filtrarCitas);
        filtroEstado.addEventListener('change', filtrarCitas);
        filtrarCitas(); // Aplicar filtros iniciales
    });

    // Función para cancelar cita
    function cancelarCita(citaId) {
        if (confirm('¿Está seguro que desea cancelar esta cita?')) {
            fetch(`/paciente/citas/${citaId}/cancelar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Cita cancelada exitosamente');
                    location.reload();
                } else {
                    alert('Error al cancelar la cita: ' + (data.error || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cancelar la cita');
            });
        }
    }

    // Función para reprogramar cita
    function reprogramarCita(citaId) {
        alert('Funcionalidad de reprogramar en desarrollo. Por favor, cancele esta cita y agende una nueva.');
    }

    // Mostrar alertas si hay mensajes de sesión
    @if(session('success'))
        setTimeout(() => {
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.innerHTML = `
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 5000);
        }, 100);
    @endif

    @if(session('error'))
        setTimeout(() => {
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show position-fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.innerHTML = `
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 5000);
        }, 100);
    @endif
</script>
@endsection
