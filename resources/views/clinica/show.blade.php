@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmoderador')
<div class="container mt-4 text-light bg-dark p-3 rounded">
    <h2>Información de la Clínica</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <strong>Nombre:</strong> {{ $clinica->nombre ?? 'No especificado' }}
    </div>

    <div class="mb-3">
        <strong>Dirección:</strong> {{ $clinica->direccion ?? 'No especificado' }}
    </div>

    <div class="mb-3">
        <strong>Teléfono:</strong> {{ $clinica->telefono ?? 'No especificado' }}
    </div>

    <div class="mb-3">
        <strong>Correo Electrónico:</strong> {{ $clinica->email ?? 'No especificado' }}
    </div>

    <div class="mb-3">
        <strong>Responsable:</strong> {{ $clinica->responsable ?? 'No especificado' }}
    </div>

    @if(session('usuario_rol') === 'administrador')
        <a href="{{ route('clinica.edit') }}" class="btn btn-primary">Editar Información</a>
    @endif
</div>
@endsection
