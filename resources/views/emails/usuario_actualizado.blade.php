<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación de cambios en tu cuenta</title>
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
            background: linear-gradient(135deg, #fd7e14, #e55100);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
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
        .alert-info {
            background: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .changes-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .changes-section h3 {
            color: #fd7e14;
            margin-top: 0;
            font-size: 18px;
        }
        .change-item {
            background: white;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
            border-left: 3px solid #007bff;
        }
        .change-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }
        .change-value {
            color: #007bff;
            font-weight: 500;
        }
        .change-old {
            color: #6c757d;
            font-style: italic;
            text-decoration: line-through;
            margin-right: 10px;
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
            font-size: 28px;
            font-weight: bold;
            color: white;
            margin-bottom: 10px;
        }
        .timestamp {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            color: #495057;
            margin: 15px 0;
        }
        .security-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .security-note strong {
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">MEDITECH</div>
            <h1>Cambios en tu cuenta</h1>
            <p class="subtitle">Tu información ha sido actualizada</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p><strong>Hola {{ $usuario->nombre }},</strong></p>
            
            <p>Te notificamos que la información de tu cuenta en Meditech ha sido actualizada recientemente.</p>

            <div class="alert-info">
                <strong>📧 Cuenta afectada:</strong> {{ $usuario->correo }}
            </div>

            <!-- Timestamp -->
            <div class="timestamp">
                <strong>📅 Fecha y hora del cambio:</strong> {{ now()->format('d/m/Y \a \l\a\s H:i') }}
            </div>

            @if(!empty($cambios))
            <!-- Changes Section -->
            <div class="changes-section">
                <h3>📝 Cambios realizados:</h3>
                
                @foreach($cambios as $campo => $valores)
                <div class="change-item">
                    <div class="change-label">
                        @switch($campo)
                            @case('nombre')
                                👤 Nombre completo:
                                @break
                            @case('correo')
                                📧 Correo electrónico:
                                @break
                            @case('estado')
                                🔘 Estado de cuenta:
                                @break
                            @case('rol')
                                👥 Rol de usuario:
                                @break
                            @default
                                {{ ucfirst($campo) }}:
                        @endswitch
                    </div>
                    <div>
                        @if(isset($valores['anterior']) && isset($valores['nuevo']))
                            <span class="change-old">{{ $valores['anterior'] }}</span>
                            <span class="change-value">{{ $valores['nuevo'] }}</span>
                        @else
                            <span class="change-value">{{ $valores }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if($actualizadoPor)
            <div class="timestamp">
                <strong>🔧 Actualizado por:</strong> {{ $actualizadoPor }}
            </div>
            @endif

            <!-- Security Note -->
            <div class="security-note">
                <strong>🔒 Nota de seguridad:</strong><br>
                Si no solicitaste estos cambios o no los reconoces, contacta inmediatamente con nuestro equipo de soporte.
            </div>

            <p>Si tienes alguna pregunta sobre estos cambios, no dudes en contactarnos.</p>

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
