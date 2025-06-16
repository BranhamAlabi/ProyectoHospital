@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmoderador')

<div class="container mt-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0"><i class="fas fa-user-md me-2"></i>Listado de Médicos</h2>
                <a href="{{ route('medicos.create') }}" class="btn btn-light">
                    <i class="fas fa-plus-circle me-1"></i>Asignar Médico
                </a>
            </div>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('medicos.index') }}" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="clinica" class="form-label fw-bold">Clínica</label>
                    <select name="clinica" id="clinica" class="form-select shadow-sm">
                        <option value="">Todas</option>
                        @foreach($clinicas as $clinica)
                            <option value="{{ $clinica->id }}" @selected(request('clinica') == $clinica->id)>{{ $clinica->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="nombre" class="form-label fw-bold">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="{{ request('nombre') }}" 
                           class="form-control shadow-sm" placeholder="Nombre del médico">
                </div>
                <div class="col-md-3">
                    <label for="especialidad" class="form-label fw-bold">Especialidad</label>
                    <input type="text" name="especialidad" id="especialidad" value="{{ request('especialidad') }}" 
                           class="form-control shadow-sm" placeholder="Especialidad">
                </div>
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="fas fa-filter me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('medicos.index') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fas fa-broom me-1"></i>Limpiar
                    </a>
                </div>
            </form>

            <div class="table-responsive rounded">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th class="fw-bold"><i class="fas fa-user me-1"></i>Nombre</th>
                            <th class="fw-bold"><i class="fas fa-envelope me-1"></i>Correo</th>
                            <th class="fw-bold"><i class="fas fa-stethoscope me-1"></i>Especialidad</th>
                            <th class="fw-bold"><i class="fas fa-hospital me-1"></i>Clínica</th>
                            <th class="fw-bold text-center"><i class="fas fa-cogs me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        @forelse($medicos as $medico)
                        <tr class="hover-shadow">
                            <td class="fw-semibold">{{ $medico->usuario->nombre ?? 'N/A' }}</td>
                            <td>{{ $medico->usuario->correo ?? 'N/A' }}</td>
                            <td>
                                @if($medico->especialidades && $medico->especialidades->count() > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($medico->especialidades as $especialidad)
                                            <span class="badge rounded-pill bg-info text-dark p-2">
                                                <i class="fas fa-certificate me-1"></i>{{ $especialidad->especialidad }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">Sin especialidad</span>
                                @endif
                            </td>
                            <td>
                                @if($medico->clinicas && $medico->clinicas->count() > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($medico->clinicas as $clinica)
                                            <span class="badge rounded-pill bg-success p-2">
                                                <i class="fas fa-map-marker-alt me-1"></i>{{ $clinica->nombre }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">Sin clínica</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('medicos.edit', $medico->id) }}" class="btn btn-sm btn-primary shadow-sm">
                                        <i class="fas fa-edit me-1"></i>Editar
                                    </a>
                                    <form action="{{ route('medicos.destroy', $medico->id) }}" method="POST" 
                                          style="display:inline-block;" 
                                          onsubmit="return confirm('¿Está seguro de eliminar este médico?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm">
                                            <i class="fas fa-trash-alt me-1"></i>Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>No se encontraron médicos.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $medicos->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    
    .table-primary {
        background: linear-gradient(135deg, #1a4b8c 0%, #12153b 100%);
        color: white;
    }
    
    .badge {
        font-size: 0.85rem;
    }
    
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .table {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .form-control, .form-select, .btn {
        border-radius: 0.375rem;
    }
</style>
@endsection