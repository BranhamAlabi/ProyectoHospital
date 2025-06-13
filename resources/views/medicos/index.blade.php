@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmoderador')

<div class="container mt-4">
    <h2>Listado de Médicos</h2>

    <a href="{{ route('medicos.create') }}" class="btn btn-success mb-3">Asignar Médico</a>

    <form method="GET" action="{{ route('medicos.index') }}" class="row g-3 mb-4">
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
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="{{ request('nombre') }}" class="form-control" placeholder="Nombre del médico">
        </div>
        <div class="col-md-3">
            <label for="especialidad" class="form-label">Especialidad</label>
            <input type="text" name="especialidad" id="especialidad" value="{{ request('especialidad') }}" class="form-control" placeholder="Especialidad">
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('medicos.index') }}" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Especialidad</th>
                    <th>Clínica</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicos as $medico)
                <tr>
                    <td>{{ $medico->usuario->nombre ?? 'N/A' }}</td>
                    <td>{{ $medico->usuario->correo ?? 'N/A' }}</td>
                    <td>
                        @if($medico->especialidades && $medico->especialidades->count() > 0)
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($medico->especialidades as $especialidad)
                                    <span class="badge bg-info text-dark">{{ $especialidad->especialidad }}</span>
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
                                    <span class="badge bg-success">{{ $clinica->nombre }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-muted">Sin clínica</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('medicos.edit', $medico->id) }}" class="btn btn-sm btn-primary">Editar</a>
                        <form action="{{ route('medicos.destroy', $medico->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Está seguro de eliminar este médico?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No se encontraron médicos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $medicos->links() }}
    </div>
</div>
@endsection
