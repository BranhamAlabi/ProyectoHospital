<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Actualización de cita médica</title>
</head>
<body>
    <h2>Actualización de cita médica</h2>
    <p>Estimado/a,</p>
    <p>La cita médica ha sido actualizada con la siguiente información:</p>
    <ul>
        <li><strong>Estado:</strong> {{ $cita->estado }}</li>
        <li><strong>Comentario:</strong> {{ $cita->comentarios ?? 'Sin comentarios' }}</li>
        <li><strong>Gestionado por:</strong> {{ $updatedBy->correo }}</li>
    </ul>
    <p>Gracias.</p>
</body>
</html>
