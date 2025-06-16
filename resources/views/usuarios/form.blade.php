<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MediTech - {{ isset($usuario) ? 'Editar' : 'Crear' }} Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #12153b;
            --primary-blue: #1a4b8c;
            --accent-blue: #3a7bd5;
            --success-green: #2e6136;
            --success-hover: #3d7d49;
            --white: #ffffff;
            --text-light: #f3f3f3;
            --glass-bg: rgba(34, 49, 84, 0.9);
        }

        body {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            padding: 2rem 0;
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: var(--white);
        }

        .form-title {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: var(--white);
            text-align: center;
        }

        .form-subtitle {
            color: rgba(255, 255, 255, 0.8);
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1rem;
        }

        .form-label {
            color: var(--text-light);
            font-weight: 500;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
        }

        .form-label i {
            margin-right: 0.5rem;
            color: var(--accent-blue);
        }

        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--white);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            background-color: rgba(255, 255, 255, 0.15);
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.2);
            color: var(--white);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-select option {
            background-color: var(--primary-dark);
            color: var(--white);
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            z-index: 10;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: var(--accent-blue);
        }

        .btn-custom {
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            border: none;
        }

        .btn-success-custom {
            background: linear-gradient(to right, var(--success-green), #3a7d46);
            color: var(--white);
        }

        .btn-success-custom:hover {
            background: linear-gradient(to right, var(--success-hover), #4d8d59);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: var(--white);
        }

        .btn-secondary-custom {
            background: transparent;
            color: rgba(255, 255, 255, 0.8);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-secondary-custom:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.15);
            border-left: 4px solid #ef4444;
            color: #fecaca;
        }

        .invalid-feedback {
            color: #fecaca;
        }

        .form-text {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.875rem;
        }

        .input-group {
            position: relative;
        }

        @media (max-width: 768px) {
            .form-container {
                margin: 0 1rem;
                padding: 2rem 1.5rem;
            }

            .form-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    @include('Plantillas.navmoderador')

    <div class="container">
        <div class="form-container">
            <h1 class="form-title">
                <i class="fas {{ isset($usuario) ? 'fa-user-edit' : 'fa-user-plus' }} me-2"></i>
                {{ isset($usuario) ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}
            </h1>
            <p class="form-subtitle">
                {{ isset($usuario) ? 'Modifica la información del usuario' : 'Completa los datos para crear un nuevo usuario' }}
            </p>

            @if($errors->any())
                <div class="alert alert-danger">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Errores encontrados:</h6>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ isset($usuario) ? route('usuarios.update', $usuario->id) : route('usuarios.store') }}" class="needs-validation" novalidate>
                @csrf
                @if(isset($usuario))
                    @method('PUT')
                @endif

                <div class="row g-4">
                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">
                            <i class="fas fa-user"></i>Nombre completo
                        </label>
                        <input type="text" id="nombre" name="nombre" class="form-control" 
                               value="{{ old('nombre', $usuario->nombre ?? '') }}" 
                               placeholder="Ej: Juan Pérez" required>
                    </div>

                    <!-- Correo -->
                    <div class="col-md-6">
                        <label for="correo" class="form-label">
                            <i class="fas fa-envelope"></i>Correo electrónico
                        </label>
                        <input type="email" id="correo" name="correo" 
                               class="form-control @error('correo') is-invalid @enderror" 
                               value="{{ old('correo', $usuario->correo ?? '') }}" 
                               placeholder="ejemplo@meditech.com" required autocomplete="email">
                        @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div class="col-md-6">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>Contraseña
                        </label>
                        <div class="input-group position-relative">
                            <input type="password" id="password" name="password" class="form-control" 
                                   placeholder="Mínimo 8 caracteres" 
                                   {{ isset($usuario) ? '' : 'required' }} autocomplete="new-password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        @if(isset($usuario))
                            <small class="form-text">Dejar en blanco para mantener la contraseña actual</small>
                        @endif
                    </div>

                    <!-- Rol -->
                    <div class="col-md-6">
                        <label for="rol_id" class="form-label">
                            <i class="fas fa-user-tag"></i>Rol del usuario
                        </label>
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

                    <!-- Estado -->
                    <div class="col-md-6">
                        <label for="estado" class="form-label">
                            <i class="fas fa-toggle-on"></i>Estado del usuario
                        </label>
                        <select id="estado" name="estado" class="form-select" required>
                            <option value="activo" {{ (old('estado', $usuario->estado ?? '') == 'activo') ? 'selected' : '' }}>
                                Activo
                            </option>
                            <option value="inactivo" {{ (old('estado', $usuario->estado ?? '') == 'inactivo') ? 'selected' : '' }}>
                                Inactivo
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="d-flex gap-3 mt-4 justify-content-center">
                    <button type="submit" class="btn btn-custom btn-success-custom">
                        <i class="fas {{ isset($usuario) ? 'fa-save' : 'fa-user-plus' }} me-2"></i>
                        {{ isset($usuario) ? 'Guardar Cambios' : 'Crear Usuario' }}
                    </button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-custom btn-secondary-custom">
                        <i class="fas fa-arrow-left me-2"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.parentElement.querySelector('.password-toggle i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Validación de formulario
        (function() {
            'use strict'
            
            const forms = document.querySelectorAll('.needs-validation')
            
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>
