<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Bienvenido a Meditech!</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .header .subtitle {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 20px;
        }
        .welcome-text {
            font-size: 18px;
            color: #007bff;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .benefits {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .benefits h3 {
            color: #007bff;
            margin-top: 0;
            font-size: 18px;
        }
        .benefits ul {
            list-style: none;
            padding: 0;
        }
        .benefits li {
            padding: 8px 0;
            position: relative;
            padding-left: 25px;
        }
        .benefits li:before {
            content: "✓";
            color: #28a745;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        .cta-section {
            text-align: center;
            margin: 30px 0;
        }
        .cta-button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: background 0.3s;
        }
        .cta-button:hover {
            background: #0056b3;
            color: white;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }
        .footer p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 14px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: white;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">MEDITECH</div>
            <h1>¡Bienvenido a nuestra plataforma!</h1>
            <p class="subtitle">Tu salud es nuestra prioridad</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="welcome-text">¡Hola {{ $usuario->nombre }}!</p>
            
            <p>Nos complace darte la más cordial bienvenida a <strong>Meditech</strong>, tu nueva plataforma integral de gestión médica.</p>
            
            <p>Tu registro ha sido completado exitosamente y ya puedes comenzar a disfrutar de todos nuestros servicios.</p>

            <!-- Benefits Section -->
            <div class="benefits">
                <h3>¿Qué puedes hacer en Meditech?</h3>
                <ul>
                    <li><strong>Agendar citas médicas</strong> de forma rápida y sencilla</li>
                    <li><strong>Consultar tus horarios</strong> y historial de citas</li>
                    <li><strong>Gestionar tu expediente médico</strong> digital</li>
                    <li><strong>Recibir notificaciones</strong> sobre tus citas y tratamientos</li>
                    <li><strong>Acceder desde cualquier dispositivo</strong> las 24 horas</li>
                    <li><strong>Comunicarte con tu equipo médico</strong> de manera segura</li>
                </ul>
            </div>

            <!-- Call to Action -->
            <div class="cta-section">
                <p>¡Comienza ahora mismo a usar la plataforma!</p>
                <a href="{{ url('/login') }}" class="cta-button">Iniciar Sesión</a>
            </div>

            <p>Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos. Nuestro equipo de soporte está aquí para asistirte.</p>

            <p>Gracias por confiar en nosotros para el cuidado de tu salud.</p>

            <p style="margin-top: 30px;">
                <strong>Atentamente,<br>
                El equipo de Meditech</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Meditech - Plataforma de Gestión Médica</strong></p>
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            <p>Si necesitas ayuda, contacta a nuestro equipo de soporte.</p>
        </div>
    </div>
</body>
</html>
