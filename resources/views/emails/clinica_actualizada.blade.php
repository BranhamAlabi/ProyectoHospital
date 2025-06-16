<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de clínica actualizada</title>
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
            background: linear-gradient(135deg, #ffc107, #e0a800);
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
        .changes-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .changes-section h3 {
            color: #ffc107;
            margin-top: 0;
        }
        .change-item {
            background: white;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
            border-left: 3px solid #ffc107;
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
            <h1>🏥 Clínica Actualizada</h1>
            <p>Se ha modificado la información de una clínica</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p><strong>Estimado responsable,</strong></p>
            
            <p>Te notificamos que se ha actualizado la información de la clínica <strong>{{ $clinica->nombre }}</strong> en el sistema Meditech.</p>

            <!-- Timestamp -->
            <div class="timestamp">
                <strong>📅 Fecha de actualización:</strong> {{ now()->format('d/m/Y \a \l\a\s H:i') }}
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
                                🏥 Nombre de la clínica:
                                @break
                            @case('direccion')
                                📍 Dirección:
                                @break
                            @case('telefono')
                                📞 Teléfono:
                                @break
                            @case('correo')
                                📧 Correo electrónico:
                                @break
                            @case('responsable')
                                👤 Responsable:
                                @break
                            @case('estado')
                                🔘 Estado:
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

            @if($actualizadaPor)
            <div class="timestamp">
                <strong>🔧 Actualizada por:</strong> {{ $actualizadaPor }}
            </div>
            @endif

            <p>Los cambios ya están vigentes en el sistema.</p>

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
