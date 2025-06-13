<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MediTech!</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #374563; /* Fondo principal */
      font-family: Arial, sans-serif;
      color: white;
    }
    header {
      background-color: #12153b;
      padding: 1rem 2rem;
      font-weight: bold;
      font-size: 1.5rem;
      font-family: 'Impact', sans-serif;
      letter-spacing: 2px;
      color: white;
    }
    .btn-green {
      background-color: #2e6136;
      border: none;
      color: white;
    }
    .btn-green:hover {
      background-color: #3d7d49;
      color: white;
    }
    .btn-purple {
      background-color: #5c59e6;
      border: none;
      color: white;
    }
    .btn-purple:hover {
      background-color: #7b78f2;
      color: white;
    }
    .filter-label {
      color: white;
      font-weight: 500;
      line-height: 2.4;
    }
    .table thead {
      background-color: #12153b;
    }
    .table thead th {
      color: white;
    }
    .table tbody tr td {
      vertical-align: middle;
    }
    @media (max-width: 576px) {
      .table-responsive {
        font-size: 0.9rem;
      }
      header {
        font-size: 1.2rem;
        padding: 0.8rem 1rem;
      }
    }
  </style>
</head>
<body>

<div class="container py-4">
  <header class="bg-primary text-white p-3 mb-4" style="font-family: 'Impact', sans-serif; font-size: 1.5rem;">
    <img style="width: 5rem;" src="{{ asset('image/meditech_logo.png') }}" alt="logo">
  </header>

  <div class="bg-secondary text-white rounded p-4 shadow-sm">
    <h4>{{ isset($usuario) ? 'Editar Usuario' : 'Crear Usuario' }}</h4>

    {{-- Mostrar mensajes de error --}}
    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ isset($usuario) ? route('usuarios.update', $usuario->id) : route('usuarios.store') }}">
      @csrf
      @if(isset($usuario))
        @method('PUT')
      @endif

      <div class="mb-3">
        <label for="nombre" class="form-label text-white">Nombre:</label>
        <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $usuario->nombre ?? '') }}" required>
      </div>

      <div class="mb-3">
        <label for="correo" class="form-label text-white">Correo electrónico:</label>
        <input type="email" id="correo" name="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo', $usuario->correo ?? '') }}" required autocomplete="email" autocorrect="off" autocapitalize="none" spellcheck="false">
        @error('correo')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="password" class="form-label text-white">Contraseña:</label>
        <input type="password" id="password" name="password" class="form-control" {{ isset($usuario) ? '' : 'required' }} autocomplete="new-password" autocorrect="off" autocapitalize="none" spellcheck="false">
        @if(isset($usuario))
          <small class="form-text text-muted">Dejar en blanco para mantener la contraseña actual.</small>
        @endif
      </div>

      <div class="mb-3">
        <label for="rol_id" class="form-label text-white">Rol:</label>
        <select id="rol_id" name="rol_id" class="form-select" required>
            <option value="" disabled {{ !isset($usuario) ? 'selected' : '' }}>Seleccionar rol</option>
            @foreach($roles as $rol)
            <option value="{{ $rol->id }}"
                {{ isset($usuario) && $usuario->roles->contains($rol->id) ? 'selected' : '' }}>
                {{ ucfirst($rol->nombre) }}
            </option>
            @endforeach
        </select>
        </div>


      <div class="mb-3">
        <label for="estado" class="form-label text-white">Estado:</label>
        <select id="estado" name="estado" class="form-select" required>
          <option value="activo" {{ (old('estado', $usuario->estado ?? '') == 'activo') ? 'selected' : '' }}>Activo</option>
          <option value="inactivo" {{ (old('estado', $usuario->estado ?? '') == 'inactivo') ? 'selected' : '' }}>Inactivo</option>
        </select>
      </div>

      <div class="d-flex gap-3">
        <button type="submit" class="btn btn-success">{{ isset($usuario) ? 'Guardar' : 'Crear' }}</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
