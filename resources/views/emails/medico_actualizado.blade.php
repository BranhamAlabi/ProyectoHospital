<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Médico Actualizada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .changes-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .changes-table th, .changes-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .changes-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .changes-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .value-old {
            color: #dc3545;
            text-decoration: line-through;
        }
        .value-new {
            color: #28a745;
            font-weight: bold;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .info-section {
            background-color: #e7f3ff;
            border: 1px solid #b3d9ff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .specialties, .clinics {
            margin: 10px 0;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            background-color: #17a2b8;
            color: white;
            border-radius: 12px;
            font-size: 12px;
            margin: 2px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 Información de Médico Actualizada</h1>
            <p>{{ config('app.name') }}</p>
        </div>
        
        <div class="content">
            <div class="alert">
                <strong>Estimado(a) Dr(a). {{ $medico->usuario->nombre }},</strong><br>
                Te informamos que tu información como médico en nuestro sistema ha sido actualizada.
            </div>

            <div class="info-section">
                <h3>📋 Información Actual</h3>
                <p><strong>Nombre:</strong> {{ $medico->usuario->nombre }}</p>
                <p><strong>Correo:</strong> {{ $medico->usuario->correo }}</p>
                
                <div class="specialties">
                    <strong>Especialidades:</strong><br>
                    @if($medico->especialidades && $medico->especialidades->count() > 0)
                        @foreach($medico->especialidades as $especialidad)
                            <span class="badge">{{ $especialidad->especialidad }}</span>
                        @endforeach
                    @else
                        <span style="color: #6c757d;">Sin especialidades asignadas</span>
                    @endif
                </div>

                <div class="clinics">
                    <strong>Clínicas asignadas:</strong><br>
                    @if($medico->clinicas && $medico->clinicas->count() > 0)
                        @foreach($medico->clinicas as $clinica)
                            <span class="badge" style="background-color: #28a745;">{{ $clinica->nombre }}</span>
                        @endforeach
                    @else
                        <span style="color: #6c757d;">Sin clínicas asignadas</span>
                    @endif
                </div>
            </div>

            @if(!empty($cambios))
            <h3>📋 Detalles de los cambios realizados</h3>
            <table class="changes-table">
                <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Valor anterior</th>
                        <th>Nuevo valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cambios as $campo => $valores)
                    <tr>
                        <td><strong>{{ ucfirst(str_replace('_', ' ', $campo)) }}</strong></td>
                        <td class="value-old">{{ $valores['anterior'] }}</td>
                        <td class="value-new">{{ $valores['nuevo'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

            <div style="margin-top: 30px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
                <p><strong>Actualizado por:</strong> {{ $actualizadoPor }}</p>
                <p><strong>Fecha y hora:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
            </div>

            <p style="margin-top: 20px;">
                Si tienes alguna duda sobre estos cambios o necesitas asistencia, no dudes en contactar al equipo de administración.
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
            <p>Este es un correo automático, por favor no responder directamente.</p>
        </div>
    </div>
</body>
</html>
