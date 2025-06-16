<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - MediTech</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #5c59e6, #7b78f2);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 300;
        }
        .content {
            padding: 30px 20px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #5c59e6, #7b78f2);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .btn:hover {
            background: linear-gradient(135deg, #4a47d1, #6865e8);
            color: white;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Recuperar Contraseña</h1>
            <p>MediTech - Sistema Hospitalario</p>
        </div>
        
        <div class="content">
            <p><strong>Hola {{ $nombre }},</strong></p>
            
            <p>Has solicitado restablecer tu contraseña en el sistema MediTech.</p>
            
            <p>Para crear una nueva contraseña, haz clic en el siguiente botón:</p>
            
            <div style="text-align: center;">
                <a href="{{ $enlace }}" class="btn">Restablecer Contraseña</a>
            </div>
            
            <p><small>O copia y pega este enlace en tu navegador:</small></p>
            <p style="word-break: break-all; background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                {{ $enlace }}
            </p>
            
            <div class="warning">
                <strong>⚠️ Importante:</strong> Este enlace es válido por <strong>24 horas</strong> únicamente. Si no lo usas en ese tiempo, deberás solicitar un nuevo enlace de recuperación.
            </div>
            
            <p>Si no solicitaste este cambio, puedes ignorar este mensaje. Nadie más podrá cambiar tu contraseña sin acceso a tu correo electrónico.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} MediTech. Todos los derechos reservados.</p>
            <p>Este es un correo automático, por favor no responder directamente.</p>
        </div>
    </div>
</body>
</html>
