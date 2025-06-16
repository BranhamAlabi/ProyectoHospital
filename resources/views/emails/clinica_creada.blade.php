<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva clínica registrada</title>
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
            background: linear-gradient(135deg, #28a745, #20c997);
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
        .clinic-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .clinic-info h3 {
            color: #28a745;
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
            min-width: 120px;
        }
        .info-value {
            color: #007bff;
            font-weight: 500;
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
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">MEDITECH</div>
            <h1>🏥 Nueva Clínica Registrada</h1>
            <p>Se ha añadido una nueva clínica al sistema</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p><strong>Estimado responsable,</strong></p>
            
            <p>Te notificamos que se ha registrado una nueva clínica en el sistema Meditech.</p>

            <!-- Timestamp -->
            <div class="timestamp">
                <strong>📅 Fecha de registro:</strong> {{ now()->format('d/m/Y \a \l\a\s H:i') }}
            </div>

            <!-- Clinic Information -->
            <div class="clinic-info">
                <h3>📋 Información de la clínica:</h3>
                
                <div class="info-item">
                    <div class="info-label">🏥 Nombre:</div>
                    <div class="info-value">{{ $clinica->nombre }}</div>
                </div>

                @if($clinica->direccion)
                <div class="info-item">
                    <div class="info-label">📍 Dirección:</div>
                    <div class="info-value">{{ $clinica->direccion }}</div>
                </div>
                @endif

                @if($clinica->telefono)
                <div class="info-item">
                    <div class="info-label">📞 Teléfono:</div>
                    <div class="info-value">{{ $clinica->telefono }}</div>
                </div>
                @endif

                @if($clinica->correo)
                <div class="info-item">
                    <div class="info-label">📧 Correo:</div>
                    <div class="info-value">{{ $clinica->correo }}</div>
                </div>
                @endif

                @if($clinica->responsable)
                <div class="info-item">
                    <div class="info-label">👤 Responsable:</div>
                    <div class="info-value">{{ $clinica->responsable }}</div>
                </div>
                @endif

                <div class="info-item">
                    <div class="info-label">🔘 Estado:</div>
                    <div class="info-value">{{ ucfirst($clinica->estado) }}</div>
                </div>
            </div>

            @if($creadaPor)
            <div class="timestamp">
                <strong>🔧 Registrada por:</strong> {{ $creadaPor }}
            </div>
            @endif

            <p>La clínica ya está disponible en el sistema y los médicos pueden ser asignados a ella.</p>

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
