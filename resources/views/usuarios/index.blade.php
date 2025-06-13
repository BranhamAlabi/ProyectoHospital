@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmoderador')

<div class="container py-4 text-light bg-dark rounded">
  <header class="bg-dark text-white p-3 mb-4" style="font-family: 'Impact', sans-serif; font-size: 1.5rem;">
    <img style="width: 5rem;" src="{{ asset('image/meditech_logo.png') }}" alt="logo">
  </header>

  <div class="mb-3">
    <a href="{{ route('usuarios.create') }}" class="btn btn-success">Crear Nuevo Usuario</a>
  </div>

  <form method="GET" action="#" class="row g-3 align-items-center mb-4">
    <div class="col-auto">
      <label for="rol" class="form-label text-white">Filtro:</label>
      <select name="rol" id="rol" class="form-select">
        <option value="">Rol</option>
        @foreach($roles as $rol)
          <option value="{{ $rol }}" @selected(request('rol') == $rol)>{{ ucfirst($rol) }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-auto">
      <label for="estado" class="form-label text-white">Estado:</label>
      <select name="estado" id="estado" class="form-select">
        <option value="">Estado</option>
        <option value="activo" @selected(request('estado') == 'activo')>Activo</option>
        <option value="inactivo" @selected(request('estado') == 'inactivo')>Inactivo</option>
      </select>
    </div>
    <div class="col-auto">
      <label for="buscar" class="form-label text-white">Buscar:</label>
      <input type="text" name="buscar" id="buscar" value="{{ request('buscar') }}" class="form-control" placeholder="Buscar por nombre o correo..." />
    </div>
    <div class="col-auto align-self-end">
      <button type="submit" class="btn btn-success">Buscar</button>
    </div>
  </form>

  <div class="table-responsive bg-white rounded shadow">
    <table class="table mb-0">
      <thead class="table-dark">
        <tr>
          <th>Nombre</th>
          <th>Rol(es)</th>
          <th>Estado</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($usuarios as $usuario)
          <tr>
            <td>{{ $usuario->nombre }}</td>
            <td>
              @foreach($usuario->roles as $rol)
                <span class="badge bg-primary text-white">{{ ucfirst($rol->nombre) }}</span>
              @endforeach
            </td>
            <td>{{ ucfirst($usuario->estado) }}</td>
            <td>
                <a href="{{ route('usuarios.show', $usuario->id) }}" class="btn btn-primary btn-sm">Ver...</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="text-center">No se encontraron usuarios.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">
    {{ $usuarios->links() }}
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
