<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación como médico</title>
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
            background: linear-gradient(135deg, #17a2b8, #138496);
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
        .assignment-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .assignment-info h3 {
            color: #17a2b8;
            margin-top: 0;
        }
        .info-item {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
            min-width: 140px;
        }
        .info-value {
            color: #007bff;
            font-weight: 500;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            background: #17a2b8;
            color: white;
            border-radius: 4px;
            font-size: 12px;
            margin: 2px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
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
        .welcome-note {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">MEDITECH</div>
            <h1>👩‍⚕️ Asignación como Médico</h1>
            <p>Has sido registrado en nuestra plataforma médica</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p><strong>Estimado/a {{ $medico->usuario->nombre ?? 'Doctor/a' }},</strong></p>
            
            <p>Nos complace informarte que has sido asignado/a como médico en la plataforma Meditech.</p>

            <!-- Welcome Note -->
            <div class="welcome-note">
                <strong>🎉 ¡Bienvenido/a al equipo médico de Meditech!</strong><br>
                Ya puedes acceder a todas las funcionalidades de la plataforma para gestionar tus citas y pacientes.
            </div>

            <!-- Timestamp -->
            <div class="timestamp">
                <strong>📅 Fecha de asignación:</strong> {{ now()->format('d/m/Y \a \l\a\s H:i') }}
            </div>

            <!-- Assignment Information -->
            <div class="assignment-info">
                <h3>📋 Información de tu asignación:</h3>
                
                <div class="info-item">
                    <div class="info-label">👤 Nombre completo:</div>
                    <div class="info-value">{{ $medico->usuario->nombre ?? 'N/A' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">📧 Correo electrónico:</div>
                    <div class="info-value">{{ $medico->usuario->correo ?? 'N/A' }}</div>
                </div>

                @if($medico->especialidades && $medico->especialidades->count() > 0)
                <div class="info-item">
                    <div class="info-label">🩺 Especialidades:</div>
                    <div class="info-value">
                        @foreach($medico->especialidades as $especialidad)
                            <span class="badge">{{ $especialidad->especialidad }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($medico->clinicas && $medico->clinicas->count() > 0)
                <div class="info-item">
                    <div class="info-label">🏥 Clínicas asignadas:</div>
                    <div class="info-value">
                        @foreach($medico->clinicas as $clinica)
                            <span class="badge">{{ $clinica->nombre }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            @if($asignadoPor)
            <div class="timestamp">
                <strong>🔧 Asignado por:</strong> {{ $asignadoPor }}
            </div>
            @endif

            <!-- Next Steps -->
            <div class="assignment-info">
                <h3>📝 Próximos pasos:</h3>
                <ul>
                    <li>📅 <strong>Configura tus horarios</strong> de atención en la plataforma</li>
                    <li>📋 <strong>Revisa tu panel médico</strong> para gestionar citas</li>
                    <li>👥 <strong>Familiarízate</strong> con las herramientas de expedientes</li>
                    <li>📞 <strong>Contacta al soporte</strong> si necesitas ayuda</li>
                </ul>
            </div>

            <p>Si tienes alguna pregunta sobre tu asignación o necesitas ayuda con la plataforma, no dudes en contactarnos.</p>

            <p style="margin-top: 30px;">
                <strong>Atentamente,<br>
                El equipo de Meditech</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Meditech - Plataforma de Gestión Médica</strong></p>
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>
