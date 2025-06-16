<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registrar Usuario - MediTech!</title>
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
      --text-dark: #2d3748;
      --glass-bg: rgba(34, 49, 84, 0.8);
    }
    
    body {
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
      color: var(--text-light);
      font-family: 'Montserrat', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding-top: 2rem;
    }
    
    header {
      background-color: var(--primary-dark);
      padding: 1.5rem 2rem;
      font-weight: 700;
      font-size: 1.5rem;
      letter-spacing: 1px;
      margin-bottom: 3rem;
      color: var(--white);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      text-align: center;
      font-family: 'Impact', sans-serif;
    }
    
    .form-container {
      max-width: 460px;
      margin: 0 auto 3rem;
      background: var(--glass-bg);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 16px;
      padding: 2.5rem;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .form-title {
      font-weight: 600;
      font-size: 1.5rem;
      margin-bottom: 1.5rem;
      color: var(--white);
      text-align: center;
    }
    
    .form-label {
      color: var(--text-light);
      font-weight: 500;
      margin-bottom: 0.75rem;
      display: block;
    }
    
    .form-control {
      background-color: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: var(--white);
      padding: 0.75rem 1.25rem;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    
    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.5);
    }
    
    .form-control:focus {
      background-color: rgba(255, 255, 255, 0.15);
      border-color: var(--accent-blue);
      box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.2);
      color: var(--white);
    }
    
    .input-group-text {
      background-color: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: rgba(255, 255, 255, 0.7);
    }
    
    .btn-success {
      background: linear-gradient(to right, var(--success-green), #3a7d46);
      color: var(--white);
      border: none;
      padding: 0.75rem;
      font-weight: 600;
      border-radius: 8px;
      transition: all 0.3s ease;
      letter-spacing: 0.5px;
      margin-top: 1rem;
    }
    
    .btn-success:hover {
      background: linear-gradient(to right, var(--success-hover), #4d8d59);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      color: var(--white);
    }
    
    .text-link {
      color: var(--accent-blue);
      font-weight: 500;
      text-decoration: none;
      transition: all 0.2s ease;
    }
    
    .text-link:hover {
      color: #00d2ff;
      text-decoration: underline;
    }
    
    .alert {
      padding: 0.75rem 1rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
    }
    
    .alert-success {
      background-color: rgba(16, 185, 129, 0.15);
      border-left: 4px solid #10b981;
      color: #a7f3d0;
    }
    
    .alert-danger {
      background-color: rgba(239, 68, 68, 0.15);
      border-left: 4px solid #ef4444;
      color: #fecaca;
    }
    
    .password-hint {
      font-size: 0.85rem;
      color: rgba(255, 255, 255, 0.7);
      margin-top: 0.5rem;
    }
    
    .login-redirect {
      margin-top: 1.5rem;
      text-align: center;
      color: rgba(255, 255, 255, 0.7);
    }
    
    @media (max-width: 576px) {
      .form-container {
        padding: 2rem 1.5rem;
        margin: 0 1rem 2rem;
      }
      
      header {
        font-size: 1.3rem;
        padding: 1.2rem 1rem;
        margin-bottom: 2rem;
      }
    }
  </style>
</head>
<body>
  <header>MediTech! – Registrar Usuario</header>

  <div class="form-container">
    @if(session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if($errors->any()))
      <div class="alert alert-danger mb-4">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
      @csrf

      <div class="mb-4">
        <label for="nombre" class="form-label">Nombre completo</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" name="nombre" id="nombre" class="form-control"
                 value="{{ old('nombre') }}" placeholder="Ej: Juan Pérez" required>
        </div>
      </div>

      <div class="mb-4">
        <label for="correo" class="form-label">Correo electrónico</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" name="correo" id="correo" class="form-control"
                 value="{{ old('correo') }}" placeholder="ejemplo@meditech.com" required>
        </div>
      </div>

      <div class="mb-4">
        <label for="password" class="form-label">Contraseña</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="contrasena" id="contrasena" class="form-control"
                 placeholder="Mínimo 8 caracteres" required minlength="8">
        </div>
        <div class="password-hint">Usa mayúsculas, números y caracteres especiales</div>
      </div>

      <div class="mb-4">
        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="contrasena_confirmation" id="contrasena_confirmation"
                 class="form-control" placeholder="Repite tu contraseña" required minlength="8">
        </div>
      </div>

      <button type="submit" class="btn btn-success w-100">
        <i class="fas fa-user-plus me-2"></i>Registrarme
      </button>
    </form>

    <div class="login-redirect">
      <p class="small">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-link">Inicia sesión</a></p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>