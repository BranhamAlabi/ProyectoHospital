<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registrar Usuario - MediTech!</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #374563;
      font-family: Arial, sans-serif;
      color: white;
      min-height: 100vh;
    }
    header {
      background-color: #12153b;
      padding: 1rem 2rem;
      font-weight: bold;
      font-size: 1.5rem;
      font-family: 'Impact', sans-serif;
      letter-spacing: 2px;
      margin-bottom: 2rem;
      color: white;
    }
    .form-container {
      max-width: 400px;
      margin: 0 auto;
      background: #223154;
      border-radius: 8px;
      padding: 2rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.14);
    }
    .form-label {
      color: #f3f3f3;
    }
    .btn-success {
      background-color: #2e6136;
      border: none;
    }
    .btn-success:hover {
      background-color: #3d7d49;
    }
    .text-link {
      color: #5c59e6;
      text-decoration: none;
    }
    .text-link:hover {
      text-decoration: underline;
    }
    @media (max-width: 576px) {
      .form-container {
        padding: 1.5rem 1rem;
      }
      header {
        font-size: 1.2rem;
        padding: 0.8rem 1rem;
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
    @if($errors->any())
      <div class="alert alert-danger mb-3">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
      @csrf

      <div class="mb-3">
        <label for="nombre" class="form-label">Nombre completo:</label>
        <input type="text" name="nombre" id="nombre" class="form-control"
               value="{{ old('nombre') }}" placeholder="Nombre y apellido" required>
      </div>

      <div class="mb-3">
        <label for="correo" class="form-label">Correo electrónico:</label>
        <input type="email" name="correo" id="correo" class="form-control"
               value="{{ old('correo') }}" placeholder="correo@ejemplo.com" required>
      </div>

      {{-- Contrasena --}}
      <div class="mb-3">
        <label for="password" class="form-label">Contraseña (mín. 8 caracteres):</label>
        <input type="password" name="contrasena" id="contrasena" class="form-control"
               placeholder="********" required minlength="8">
      </div>

      {{-- Confirmar Contrasena --}}
      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirmar contraseña:</label>
        <input type="password" name="contrasena_confirmation" id="contrasena_confirmation"
               class="form-control" placeholder="********" required minlength="8">
      </div>

      <button type="submit" class="btn btn-success w-100">Registrarme</button>
    </form>

    <div class="mt-3 text-center">
      <p class="small text-light">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-link">Inicia sesión</a>
      </p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
