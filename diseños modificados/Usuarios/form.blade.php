<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MediTech! - {{ isset($usuario) ? 'Editar Usuario' : 'Crear Usuario' }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --primary-dark: #12153b;
      --primary-blue: #1a4b8c;
      --accent-blue: #3a7bd5;
      --success-green: #2e6136;
      --success-hover: #3d7d49;
      --light-blue: #e8f0fe;
      --light-gray: #f5f7fa;
      --white: #ffffff;
      --text-light: #f3f3f3;
      --glass-bg: rgba(34, 49, 84, 0.9);
    }
    
    body {
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
      color: var(--text-light);
      font-family: 'Montserrat', sans-serif;
      min-height: 100vh;
    }
    
    .header-container {
      background-color: var(--primary-dark);
      padding: 1.5rem 2rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      margin-bottom: 2rem;
    }
    
    .logo-container {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    
    .logo-img {
      height: 50px;
      width: auto;
    }
    
    .logo-text {
      font-family: 'Impact', sans-serif;
      font-size: 1.8rem;
      letter-spacing: 1px;
      color: var(--white);
      margin: 0;
    }
    
    .form-container {
      background: var(--glass-bg);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 16px;
      padding: 2.5rem;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.1);
      margin-bottom: 3rem;
    }
    
    .form-title {
      font-weight: 600;
      font-size: 1.75rem;
      margin-bottom: 1.5rem;
      color: var(--white);
      position: relative;
      padding-bottom: 0.75rem;
    }
    
    .form-title::after {
      content: '';
      position: absolute;
      left: 0;
      bottom: 0;
      width: 60px;
      height: 4px;
      background: linear-gradient(to right, var(--accent-blue), #00d2ff);
      border-radius: 2px;
    }
    
    .form-label {
      color: var(--text-light);
      font-weight: 500;
      margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
      background-color: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: var(--white);
      padding: 0.75rem 1.25rem;
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
    
    .btn-success {
      background: linear-gradient(to right, var(--success-green), #3a7d46);
      border: none;
      padding: 0.75rem 1.5rem;
      font-weight: 600;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    
    .btn-success:hover {
      background: linear-gradient(to right, var(--success-hover), #4d8d59);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .btn-secondary {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: var(--white);
      transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
      background: rgba(255, 255, 255, 0.2);
      color: var(--white);
    }
    
    .alert-danger {
      background-color: rgba(239, 68, 68, 0.15);
      border-left: 4px solid #ef4444;
      color: #fecaca;
      border-radius: 8px;
    }
    
    .invalid-feedback {
      color: #fecaca;
    }
    
    .form-text {
      color: rgba(255, 255, 255, 0.6);
      font-size: 0.85rem;
    }
    
    .action-buttons {
      display: flex;
      gap: 1rem;
      margin-top: 1.5rem;
    }
    
    @media (max-width: 768px) {
      .form-container {
        padding: 2rem 1.5rem;
      }
      
      .logo-text {
        font-size: 1.5rem;
      }
      
      .action-buttons {
        flex-direction: column;
      }
      
      .btn-success, .btn-secondary {
        width: 100%;
      }
    }
    
    @media (max-width: 576px) {
      .header-container {
        padding: 1rem;
      }
      
      .form-container {
        padding: 1.75rem 1.25rem;
      }
      
      .logo-img {
        height: 40px;
      }
      
      .logo-text {
        font-size: 1.3rem;
      }
    }
  </style>
</head>
<body>
  <div class="container py-4">
    <header class="header-container">
      <div class="logo-container">
        <img class="logo-img" src="{{ asset('image/meditech_logo.png') }}" alt="MediTech Logo">
        <h1 class="logo-text">MediTech</h1>
      </div>
    </header>

    <div class="form-container">
      <h2 class="form-title">{{ isset($usuario) ? 'Editar Usuario' : 'Crear Usuario' }}</h2>

      @if($errors->any())
        <div class="alert alert-danger mb-4">
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

        <div class="mb-4">
          <label for="nombre" class="form-label">Nombre</label>
          <input type="text" id="nombre" name="nombre" class="form-control" 
                 value="{{ old('nombre', $usuario->nombre ?? '') }}" 
                 placeholder="Ingrese el nombre completo" required>
        </div>

        <div class="mb-4">
          <label for="correo" class="form-label">Correo electrónico</label>
          <input type="email" id="correo" name="correo" 
                 class="form-control @error('correo') is-invalid @enderror" 
                 value="{{ old('correo', $usuario->correo ?? '') }}" 
                 placeholder="ejemplo@meditech.com" 
                 required autocomplete="email" autocorrect="off" autocapitalize="none">
          @error('correo')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-4">
          <label for="password" class="form-label">Contraseña</label>
          <input type="password" id="password" name="password" class="form-control" 
                 placeholder="{{ isset($usuario) ? 'Dejar en blanco para no cambiar' : 'Ingrese una contraseña segura' }}" 
                 {{ isset($usuario) ? '' : 'required' }} autocomplete="new-password">
          @if(isset($usuario))
            <small class="form-text">Dejar en blanco para mantener la contraseña actual</small>
          @endif
        </div>

        <div class="mb-4">
          <label for="rol_id" class="form-label">Rol</label>
          <select id="rol_id" name="rol_id" class="form-select" required>
            <option value="" disabled {{ !isset($usuario) ? 'selected' : '' }}>Seleccione un rol</option>
            @foreach($roles as $rol)
              <option value="{{ $rol->id }}"
                {{ isset($usuario) && $usuario->roles->contains($rol->id) ? 'selected' : '' }}>
                {{ ucfirst($rol->nombre) }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-4">
          <label for="estado" class="form-label">Estado</label>
          <select id="estado" name="estado" class="form-select" required>
            <option value="activo" {{ (old('estado', $usuario->estado ?? '') == 'activo') ? 'selected' : '' }}>Activo</option>
            <option value="inactivo" {{ (old('estado', $usuario->estado ?? '') == 'inactivo') ? 'selected' : '' }}>Inactivo</option>
          </select>
        </div>

        <div class="action-buttons">
          <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-2"></i>{{ isset($usuario) ? 'Guardar Cambios' : 'Crear Usuario' }}
          </button>
          <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-2"></i>Cancelar
          </a>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>