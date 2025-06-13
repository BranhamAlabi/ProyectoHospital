<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Restablecer Contraseña - MediTech!</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #374563;
      color: white;
      font-family: Arial, sans-serif;
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
    .btn-blue {
      background-color: #5c59e6;
      color: #fff;
      border: none;
    }
    .btn-blue:hover {
      background-color: #7b78f2;
      color: #fff;
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
  <header>
    MediTech! – Restablecer Contraseña
  </header>

  <div class="form-container">
    <h5 class="mb-3" style="font-family: 'Impact', sans-serif;">Crea tu nueva contraseña</h5>

    @if ($errors->any())
      <div class="alert alert-danger mb-3">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
      @csrf

      {{-- El token y el correo llegan desde la URL o la sesión --}}
      <input type="hidden" name="token" value="{{ $token }}" />
      <input type="hidden" name="correo" value="{{ $correo }}" />

      <div class="mb-3">
        <label for="password" class="form-label">Nueva contraseña (mín. 8 caracteres):</label>
        <input
          type="password"
          id="contrasena"
          name="contrasena"
          class="form-control"
          placeholder="********"
          required
          minlength="8"
        />
      </div>

      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirma la contraseña:</label>
        <input
          type="password"
          id="contrasena_confirmation"
          name="contrasena_confirmation"
          class="form-control"
          placeholder="********"
          required
          minlength="8"
        />
      </div>

      <button type="submit" class="btn btn-blue w-100">Actualizar contraseña</button>
    </form>

    <div class="mt-3 text-center">
      <a href="{{ route('login') }}" class="text-link">Volver al inicio de sesión</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
