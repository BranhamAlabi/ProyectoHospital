<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Actualización de información de médico</title>
</head>
<body>
    <h1>Hola {{ $usuario->nombre }},</h1>
    <p>Su información de médico ha sido actualizada exitosamente en el sistema.</p>
    <p><strong>Especialidad:</strong> {{ $medico->especialidad }}</p>
    <p><strong>Clínica:</strong> {{ $medico->clinica->nombre ?? 'N/A' }}</p>
    <p>Si usted no realizó estos cambios, por favor contacte al administrador del sistema.</p>
    <p>Gracias,</p>
    <p>Equipo de Gestión</p>
</body>
</html>
