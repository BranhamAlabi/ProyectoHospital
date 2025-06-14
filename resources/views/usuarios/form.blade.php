<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MediTech - {{ isset($usuario) ? 'Editar' : 'Crear' }} Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #374563;
            font-family: Arial, sans-serif;
            color: white;
            min-height: 100vh;
        }
        .container {
            padding-bottom: 2rem;
        }
        .form-container {
            background-color: rgba(33, 37, 41, 0.95);
            border-radius: 10px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .form-label {
            font-weight: 500;
        }
        .btn-success {
            background-color: #2e6136;
            border: none;
        }
        .btn-success:hover {
            background-color: #3d7d49;
        }
    </style>
</head>
<body>
    @include('Plantillas.navgestion')

    <div class="container">
        <div class="form-container">
            <h2 class="mb-4">{{ isset($usuario) ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}</h2>

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
                @endif                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $usuario->nombre ?? '') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="correo" class="form-label">Correo electrónico:</label>
                        <input type="email" id="correo" name="correo" class="form-control @error('correo') is-invalid @enderror" 
                            value="{{ old('correo', $usuario->correo ?? '') }}" required autocomplete="email">
                        @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" id="password" name="password" class="form-control" 
                            {{ isset($usuario) ? '' : 'required' }} autocomplete="new-password">
                        @if(isset($usuario))
                            <small class="form-text text-muted">Dejar en blanco para mantener la contraseña actual.</small>
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="rol_id" class="form-label">Rol:</label>
                        <select id="rol_id" name="rol_id" class="form-select" required>
                            <option value="" disabled {{ !isset($usuario) ? 'selected' : '' }}>Seleccionar rol</option>
                            @foreach($roles as $rol)
                                @if(in_array('administrador', session('usuario_rol', [])) || 
                                    (!in_array($rol->nombre, ['administrador', 'moderador'])))
                                    <option value="{{ $rol->id }}"
                                        {{ isset($usuario) && $usuario->roles->contains($rol->id) ? 'selected' : '' }}>
                                        {{ ucfirst($rol->nombre) }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="estado" class="form-label">Estado:</label>
                        <select id="estado" name="estado" class="form-select" required>
                            <option value="activo" {{ (old('estado', $usuario->estado ?? '') == 'activo') ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ (old('estado', $usuario->estado ?? '') == 'inactivo') ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn btn-success">{{ isset($usuario) ? 'Guardar Cambios' : 'Crear Usuario' }}</button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
