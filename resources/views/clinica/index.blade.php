@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmoderador')

<div class="container mt-4">
    <h2>Listado de Clínicas</h2>

    <form method="GET" action="{{ route('clinica.index') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="{{ request('nombre') }}" class="form-control" placeholder="Nombre de la clínica">
        </div>
        <div class="col-md-4">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" id="estado" class="form-select">
                <option value="">Todos</option>
                <option value="activo" @selected(request('estado') == 'activo')>Activo</option>
                <option value="inactivo" @selected(request('estado') == 'inactivo')>Inactivo</option>
            </select>
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('clinica.index') }}" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Responsable</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clinicas as $clinica)
                <tr>
                    <td>{{ $clinica->nombre }}</td>
                    <td>{{ $clinica->direccion ?? 'N/A' }}</td>
                    <td>{{ $clinica->telefono ?? 'N/A' }}</td>
                    <td>{{ $clinica->correo ?? 'N/A' }}</td>
                    <td>{{ $clinica->responsable ?? 'N/A' }}</td>
                    <td>{{ ucfirst($clinica->estado) }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#editForm{{ $clinica->id }}" aria-expanded="false" aria-controls="editForm{{ $clinica->id }}">
                            Editar
                        </button>
                    </td>
                </tr>
                <tr class="collapse" id="editForm{{ $clinica->id }}">
                    <td colspan="7">
                        <form method="POST" action="{{ route('clinica.update', $clinica->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-2">
                                <div class="col-md-2">
                                    <input type="text" name="nombre" class="form-control" value="{{ $clinica->nombre }}" required placeholder="Nombre">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="direccion" class="form-control" value="{{ $clinica->direccion }}" placeholder="Dirección">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="telefono" class="form-control" value="{{ $clinica->telefono }}" placeholder="Teléfono">
                                </div>
                                <div class="col-md-2">
                                    <input type="email" name="correo" class="form-control" value="{{ $clinica->correo }}" placeholder="Correo">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="responsable" class="form-control" value="{{ $clinica->responsable }}" placeholder="Responsable">
                                </div>
                                <div class="col-md-1">
                                    <select name="estado" class="form-select">
                                        <option value="activo" @selected($clinica->estado == 'activo')>Activo</option>
                                        <option value="inactivo" @selected($clinica->estado == 'inactivo')>Inactivo</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No se encontraron clínicas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
