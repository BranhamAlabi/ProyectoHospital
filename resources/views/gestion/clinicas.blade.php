@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navgestion')

<div class="container mt-4">
    <h2>Listado de Clínicas</h2>

    <form method="GET" action="{{ route('gestion.listarClinicas') }}" class="row g-3 mb-4">
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
            <a href="{{ route('gestion.listarClinicas') }}" class="btn btn-secondary">Limpiar</a>
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
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No se encontraron clínicas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
