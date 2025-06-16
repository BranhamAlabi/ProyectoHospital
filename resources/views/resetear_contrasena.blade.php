<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Restablecer Contraseña - MediTech!</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-dark: #12153b;
      --primary-blue: #1a4b8c;
      --accent-blue: #3a7bd5;
      --light-blue: #e8f0fe;
      --light-gray: #f5f7fa;
      --white: #ffffff;
      --text-light: #f3f3f3;
      --text-dark: #2d3748;
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
    }
    
    .form-container {
      max-width: 460px;
      margin: 0 auto 3rem;
      background: rgba(255, 255, 255, 0.1);
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
    
    .btn-blue {
      background: linear-gradient(to right, var(--accent-blue), #00d2ff);
      color: var(--white);
      border: none;
      padding: 0.75rem;
      font-weight: 600;
      border-radius: 8px;
      transition: all 0.3s ease;
      letter-spacing: 0.5px;
      margin-top: 1rem;
    }
    
    .btn-blue:hover {
      background: linear-gradient(to right, #2a6bc8, #00c8ff);
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
    
    .alert-danger {
      background-color: rgba(239, 68, 68, 0.15);
      border-left: 4px solid #ef4444;
      color: #fecaca;
      border-radius: 6px;
      border: none;
    }
      .password-hint {
      font-size: 0.85rem;
      color: rgba(255, 255, 255, 0.7);
      margin-top: 0.5rem;
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
  <header>
    MediTech! – Restablecer Contraseña
  </header>

  <div class="form-container">
    <h5 class="form-title">Crea tu nueva contraseña</h5>

    @if ($errors->any())
      <div class="alert alert-danger mb-4">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
      @csrf

      <input type="hidden" name="token" value="{{ $token }}" />
      <input type="hidden" name="correo" value="{{ $correo }}" />      <div class="mb-4">
        <label for="password" class="form-label">Nueva contraseña</label>
        <div class="input-group position-relative">
          <input
            type="password"
            id="contrasena"
            name="contrasena"
            class="form-control"
            placeholder="Mínimo 8 caracteres"
            required
            minlength="8"
          />
          <button type="button" class="password-toggle" onclick="togglePassword('contrasena')">
            <i class="far fa-eye"></i>
          </button>
        </div>
        <div class="password-hint">Usa mayúsculas, números y caracteres especiales</div>
      </div>

      <div class="mb-4">
        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
        <div class="input-group position-relative">
          <input
            type="password"
            id="contrasena_confirmation"
            name="contrasena_confirmation"
            class="form-control"
            placeholder="Repite tu contraseña"
            required
            minlength="8"
          />
          <button type="button" class="password-toggle" onclick="togglePassword('contrasena_confirmation')">
            <i class="far fa-eye"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-blue w-100">Actualizar contraseña</button>
    </form>

    <div class="mt-4 text-center">
      <a href="{{ route('login') }}" class="text-link">Volver al inicio de sesión</a>
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
