@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmoderador')

<div class="container mt-4">
    <h2>Listado de Citas</h2>

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
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('citas.index') }}" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
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
                <td>{{ ucfirst($cita->estado) }}</td>
                <td>{{ $cita->paciente->nombre ?? 'N/A' }}</td>
                <td>{{ $cita->medico->nombre ?? 'N/A' }}</td>
                <td>{{ $cita->clinica->nombre ?? 'N/A' }}</td>
                <td>{{ $cita->fecha }}</td>
                <td>{{ $cita->hora }}</td>
                <td>
                    <a href="{{ route('citas.show', $cita->id) }}" class="btn btn-sm btn-primary">Ver</a>
                </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No se encontraron citas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $citas->links() }}
    </div>
</div>
@endsection
