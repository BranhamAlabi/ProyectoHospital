@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navgestion')

<div class="container py-4">
  <header class="bg-primary text-white p-3 mb-4" style="font-family: 'Impact', sans-serif; font-size: 1.5rem;">
    <img style="width: 5rem;" src="{{ asset('image/meditech_logo.png') }}" alt="logo">
  </header>

  <div class="bg-secondary text-white rounded p-4 shadow-sm">
    <h4>Detalle de Usuario</h4>
    <div class="row mb-3">
      <label class="col-sm-2 col-form-label">Nombre:</label>
      <div class="col-sm-10">{{ $usuario->nombre }}</div>
    </div>
    <div class="row mb-3">
      <label class="col-sm-2 col-form-label">Email:</label>
      <div class="col-sm-10">{{ $usuario->correo }}</div>
    </div>
    <div class="row mb-3 align-items-center">
      <label class="col-sm-2 col-form-label">Rol(es):</label>
      <div class="col-sm-8">
        @foreach($usuario->roles as $rol)
          <span class="badge bg-info text-dark me-1">{{ ucfirst($rol->nombre) }}</span>
        @endforeach
      </div>
      @can('editar-usuario', $usuario) {{-- política de autorización para editar --}}
      <div class="col-sm-2">
        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-success btn-sm">Cambiar</a>
      </div>
      @endcan
    </div>
    <div class="row mb-4">
      <label class="col-sm-2 col-form-label">Estado:</label>
      <div class="col-sm-10">{{ ucfirst($usuario->estado) }}</div>
    </div>

    <div class="d-flex gap-2">

      <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-success">Editar Usuario</a>

      <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
