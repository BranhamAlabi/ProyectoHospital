<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Iniciar Sesión - MediTech!</title>
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
      --white: #ffffff;
      --text-light: #f3f3f3;
      --glass-bg: rgba(34, 49, 84, 0.9);
    }
    
    body {
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
      color: var(--text-light);
      font-family: 'Montserrat', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 2rem 0;
    }
    
    .auth-header {
      background-color: var(--primary-dark);
      padding: 1.5rem;
      font-weight: 700;
      font-size: 1.75rem;
      letter-spacing: 1px;
      color: var(--white);
      text-align: center;
      margin-bottom: 2.5rem;
      font-family: 'Impact', sans-serif;
      border-radius: 0 0 16px 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }
    
    .auth-container {
      max-width: 480px;
      width: 100%;
      margin: 0 auto;
      background: var(--glass-bg);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-radius: 16px;
      padding: 2.5rem;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.15);
    }
    
    .auth-title {
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
    }
    
    .form-control {
      background-color: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: var(--white);
      padding: 0.75rem 1.25rem;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    
    .form-control:focus {
      background-color: rgba(255, 255, 255, 0.15);
      border-color: var(--accent-blue);
      box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.2);
      color: var(--white);
    }
    
    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.5);
    }
    
    .input-group-text {
      background-color: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: rgba(255, 255, 255, 0.7);
    }
    
    .btn-login {
      background: linear-gradient(to right, var(--success-green), #3a7d46);
      border: none;
      padding: 0.75rem;
      font-weight: 600;
      border-radius: 8px;
      transition: all 0.3s ease;
      letter-spacing: 0.5px;
      margin-top: 1rem;
      width: 100%;
    }
    
    .btn-login:hover {
      background: linear-gradient(to right, var(--success-hover), #4d8d59);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .auth-link {
      color: var(--accent-blue);
      font-weight: 500;
      text-decoration: none;
      transition: all 0.2s ease;
    }
    
    .auth-link:hover {
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
    
    .auth-footer {
      text-align: center;
      margin-top: 1.5rem;
      color: rgba(255, 255, 255, 0.7);
      font-size: 0.9rem;
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
    
    @media (max-width: 576px) {
      body {
        padding: 1rem;
      }
      
      .auth-container {
        padding: 2rem 1.5rem;
      }
      
      .auth-header {
        font-size: 1.5rem;
        padding: 1.2rem;
        margin-bottom: 2rem;
      }
    }
  </style>
</head>
<body>
  <header class="auth-header">
    MediTech! – Iniciar Sesión
  </header>

  <div class="auth-container">
    @if(session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
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

    <form method="POST" action="{{ route('login.post') }}">
      @csrf
      
      <div class="mb-4">
        <label for="correo" class="form-label">Correo electrónico</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input
            type="email"
            name="correo"
            id="correo"
            class="form-control"
            placeholder="ejemplo@meditech.com"
            required
            autofocus
            value="{{ old('correo') }}"
          />
        </div>
      </div>

      <div class="mb-4">
        <label for="contrasena" class="form-label">Contraseña</label>
        <div class="input-group position-relative">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input
            type="password"
            name="contrasena"
            id="contrasena"
            class="form-control"
            placeholder="Ingresa tu contraseña"
            required
          />
          <button type="button" class="password-toggle" onclick="togglePassword('contrasena')">
            <i class="far fa-eye"></i>
          </button>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="remember" name="remember">
          <label class="form-check-label" for="remember">Recordar sesión</label>
        </div>
        <a href="{{ route('password.request') }}" class="auth-link small">¿Olvidaste tu contraseña?</a>
      </div>

      <button type="submit" class="btn btn-login">
        <i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión
      </button>
    </form>

    <div class="auth-footer">
      <p>¿Aún no tienes cuenta? <a href="{{ route('register') }}" class="auth-link">Regístrate aquí</a></p>
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
  </script>
</body>
</html>
