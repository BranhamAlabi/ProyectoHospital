@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmoderador')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Listado de Citas</h2>
        </div>
        
        <div class="card-body">
            <form method="GET" action="{{ route('citas.index') }}" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="aprobada" @selected(request('estado') == 'aprobada')>Aprobada</option>
                        <option value="cancelada" @selected(request('estado') == 'cancelada')>Cancelada</option>
                        <option value="pendiente" @selected(request('estado') == 'pendiente')>Pendiente</option>
                        <option value="pendiente_reprogramacion" @selected(request('estado') == 'pendiente_reprogramacion')>Pendiente Reprogramación</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="clinica" class="form-label">Clínica</label>
                    <select name="clinica" id="clinica" class="form-select">
                        <option value="">Todas</option>
                        @foreach($clinicas as $clinica)
                            <option value="{{ $clinica->id }}" @selected(request('clinica') == $clinica->id)>{{ $clinica->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="paciente" class="form-label">Paciente</label>
                    <input type="text" name="paciente" id="paciente" value="{{ request('paciente') }}" class="form-control" placeholder="Nombre del paciente">
                </div>
                <div class="col-md-3">
                    <label for="medico" class="form-label">Médico</label>
                    <input type="text" name="medico" id="medico" value="{{ request('medico') }}" class="form-control" placeholder="Nombre del médico">
                </div>
                <div class="col-md-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" name="fecha" id="fecha" value="{{ request('fecha') }}" class="form-control">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                    <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-broom me-1"></i> Limpiar
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Estado</th>
                            <th>Paciente</th>
                            <th>Médico</th>
                            <th>Clínica</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($citas as $cita)
                        <tr>
                            <td>
                                <span class="badge 
                                    @if($cita->estado == 'aprobada') bg-success 
                                    @elseif($cita->estado == 'cancelada') bg-danger 
                                    @elseif($cita->estado == 'pendiente') bg-warning text-dark 
                                    @else bg-info text-dark @endif">
                                    {{ ucfirst($cita->estado) }}
                                </span>
                            </td>
                            <td>{{ $cita->paciente->nombre ?? 'N/A' }}</td>
                            <td>{{ $cita->medico->usuario->nombre ?? 'N/A' }}</td>
                            <td>{{ $cita->clinica->nombre ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}</td>
                            <td>
                                <a href="{{ route('citas.show', $cita->id) }}" class="btn btn-sm btn-primary" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-calendar-times fa-2x mb-2 text-muted"></i>
                                <p class="text-muted">No se encontraron citas</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $citas->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.1);
    }
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    .form-control, .form-select {
        border-radius: 5px;
    }
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
</style>

@endsection
