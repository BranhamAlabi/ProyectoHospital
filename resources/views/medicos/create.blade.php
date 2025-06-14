@extends('Plantillas.sesion')

@section('Contenido')
@if(in_array(session('usuario_rol'), ['administrador', 'moderador']))
    @include('Plantillas.navmoderador')
@endif

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Agregar Médico</h2>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

    <form action="{{ route('medicos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="clinica_ids" class="form-label">Clínicas</label>
            <select name="clinica_ids[]" id="clinica_ids" class="form-select" multiple required>
                <option value="">Seleccione una o más clínicas</option>
                @foreach($clinicas as $clinica)
                    <option value="{{ $clinica->id }}" @selected(in_array($clinica->id, old('clinica_ids', [])))>{{ $clinica->nombre }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Mantenga presionada la tecla Ctrl (o Cmd en Mac) para seleccionar múltiples clínicas.</small>
        </div>

        <div class="mb-3">
            <label for="usuario_id" class="form-label">Médico</label>
            <select name="usuario_id" id="usuario_id" class="form-select" required>
                <option value="">Seleccione un médico</option>
                @foreach($usuariosMedicos as $usuario)
                    <option value="{{ $usuario->id }}" @selected(old('usuario_id') == $usuario->id)>{{ $usuario->nombre }} ({{ $usuario->correo }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="especialidad_ids" class="form-label">Especialidades</label>
            <select name="especialidad_ids[]" id="especialidad_ids" class="form-select" multiple required>
                @foreach($especialidades as $especialidad)
                    <option value="{{ $especialidad->id }}" @selected(in_array($especialidad->id, old('especialidad_ids', [])))>{{ $especialidad->especialidad }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Mantenga presionada la tecla Ctrl (o Cmd en Mac) para seleccionar múltiples especialidades.</small>
        </div>

        <button type="submit" class="btn btn-primary">Agregar Médico</button>
        <a href="{{ route('medicos.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
