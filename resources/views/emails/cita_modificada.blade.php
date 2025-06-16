<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu cita médica ha sido modificada</title>
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
            background: linear-gradient(135deg, #dc3545, #c82333);
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
        .appointment-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .appointment-info h3 {
            color: #dc3545;
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
        .changes-section {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .changes-section h3 {
            color: #856404;
            margin-top: 0;
        }
        .change-item {
            background: white;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
            border-left: 3px solid #dc3545;
        }
        .change-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }
        .change-value {
            color: #dc3545;
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
        .alert {
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
            <h1>📅 Cita Médica Modificada</h1>
            <p>Se han realizado cambios en tu cita</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p><strong>Estimado/a {{ $cita->paciente->nombre ?? 'Paciente' }},</strong></p>
            
            <p>Te informamos que se han realizado modificaciones en tu cita médica programada.</p>

            <!-- Alert -->
            <div class="alert">
                <strong>ℹ️ Importante:</strong> Por favor revisa los cambios realizados y asegúrate de tener en cuenta la nueva información.
            </div>

            <!-- Timestamp -->
            <div class="timestamp">
                <strong>📅 Fecha de modificación:</strong> {{ now()->format('d/m/Y \a \l\a\s H:i') }}
            </div>

            <!-- Current Appointment Information -->
            <div class="appointment-info">
                <h3>📋 Información actual de tu cita:</h3>
                
                <div class="info-item">
                    <div class="info-label">👩‍⚕️ Médico:</div>
                    <div class="info-value">{{ $cita->medico->usuario->nombre ?? 'N/A' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">🏥 Clínica:</div>
                    <div class="info-value">{{ $cita->clinica->nombre ?? 'N/A' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">📅 Fecha:</div>
                    <div class="info-value">{{ $cita->fecha }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">🕐 Hora:</div>
                    <div class="info-value">{{ $cita->hora }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">🔘 Estado:</div>
                    <div class="info-value">{{ ucfirst($cita->estado) }}</div>
                </div>

                @if($cita->motivo)
                <div class="info-item">
                    <div class="info-label">📝 Motivo:</div>
                    <div class="info-value">{{ $cita->motivo }}</div>
                </div>
                @endif

                @if($cita->comentarios)
                <div class="info-item">
                    <div class="info-label">💬 Comentarios:</div>
                    <div class="info-value">{{ $cita->comentarios }}</div>
                </div>
                @endif
            </div>

            @if(!empty($cambios))
            <!-- Changes Section -->
            <div class="changes-section">
                <h3>📝 Cambios realizados:</h3>
                
                @foreach($cambios as $campo => $valores)
                <div class="change-item">
                    <div class="change-label">
                        @switch($campo)
                            @case('fecha')
                                📅 Fecha de la cita:
                                @break
                            @case('hora')
                                🕐 Hora de la cita:
                                @break
                            @case('estado')
                                🔘 Estado de la cita:
                                @break
                            @case('comentarios')
                                💬 Comentarios:
                                @break
                            @case('motivo')
                                📝 Motivo:
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

            @if($modificadaPor)
            <div class="timestamp">
                <strong>🔧 Modificada por:</strong> {{ $modificadaPor }}
            </div>
            @endif

            <p><strong>Próximos pasos:</strong></p>
            <ul>
                <li>📱 Guarda la nueva información en tu calendario</li>
                <li>⏰ Llega 15 minutos antes de tu cita</li>
                <li>🆔 Trae tu identificación y documentos médicos relevantes</li>
                <li>📞 Contacta a la clínica si tienes alguna duda</li>
            </ul>

            <p>Si no puedes asistir con los nuevos cambios, por favor contacta lo antes posible para reprogramar.</p>

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
