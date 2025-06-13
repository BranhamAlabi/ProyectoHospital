<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Recuperar Contraseña</title>
</head>
<body>
  <p>Hola {{ $usuario->nombre }},</p>
  <p>Has solicitado restablecer tu contraseña en MediTech.</p>
  <p>Haz clic en el siguiente enlace para crear una nueva contraseña (válido 1 hora):</p>
  <p>
    <a href="{{ $resetLink }}">{{ $resetLink }}</a>
  </p>
  <br>
  <p>Si no solicitaste este cambio, ignora este mensaje. Nadie más podrá cambiar tu contraseña sin tu consentimiento.</p>
  <br>
  <p>Equipo MediTech</p>
</body>
</html>
