@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmoderador')

<div class="container mt-4">
    <h2>Editar Médico</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <form action="{{ route('medicos.update', $medico->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="clinica_ids" class="form-label">Clínicas</label>
                <select name="clinica_ids[]" id="clinica_ids" class="form-select" multiple required>
                    @foreach($clinicas as $clinica)
                        <option value="{{ $clinica->id }}" @selected(in_array($clinica->id, old('clinica_ids', $medico->clinicas->pluck('id')->toArray())))>{{ $clinica->nombre }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Mantenga presionada la tecla Ctrl (o Cmd en Mac) para seleccionar múltiples clínicas.</small>
            </div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $medico->usuario->nombre) }}" required>
        </div>

        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" name="correo" id="correo" class="form-control" value="{{ old('correo', $medico->usuario->correo) }}" required>
        </div>

        <div class="mb-3">
            <label for="especialidad_ids" class="form-label">Especialidades</label>
            <select name="especialidad_ids[]" id="especialidad_ids" class="form-select" multiple required>
                @foreach($especialidades as $especialidad)
                    <option value="{{ $especialidad->id }}" @selected(in_array($especialidad->id, old('especialidad_ids', $medico->especialidades->pluck('id')->toArray())))>{{ $especialidad->especialidad }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Mantenga presionada la tecla Ctrl (o Cmd en Mac) para seleccionar múltiples especialidades.</small>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="{{ route('medicos.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
