@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navgestion')
<div class="container mt-4">
    <h2>Editar Información de la Clínica</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('clinica.update', $clinica->id) }}">>
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $clinica->nombre ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección:</label>
            <input type="text" id="direccion" name="direccion" class="form-control" value="{{ old('direccion', $clinica->direccion ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" class="form-control" value="{{ old('telefono', $clinica->telefono ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico:</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $clinica->email ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="responsable" class="form-label">Responsable:</label>
            <input type="text" id="responsable" name="responsable" class="form-control" value="{{ old('responsable', $clinica->responsable ?? '') }}" required>
        </div>

        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="{{ route('clinica.show') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
