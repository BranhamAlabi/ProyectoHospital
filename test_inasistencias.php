<?php
/**
 * Test manual para verificar el funcionamiento de las inasistencias
 * Ejecutar con: php test_inasistencias.php
 */

require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Cita;
use App\Models\Usuarios;

echo "🧪 Probando sistema de inasistencias...\n\n";

// Buscar un paciente de prueba
$paciente = Usuarios::whereHas('roles', function($query) {
    $query->where('nombre', 'Paciente');
})->first();

if (!$paciente) {
    echo "❌ No se encontró ningún paciente en el sistema\n";
    exit(1);
}

echo "👤 Paciente encontrado: {$paciente->nombre} (ID: {$paciente->id})\n";

// Mostrar estadísticas actuales
$estadisticas = Cita::obtenerEstadisticasAsistencia($paciente->id);
echo "\n📊 Estadísticas actuales:\n";
echo "   - Total evaluadas: {$estadisticas['total_evaluadas']}\n";
echo "   - Asistencias: {$estadisticas['asistencias']}\n";
echo "   - Inasistencias: {$estadisticas['inasistencias']}\n";
echo "   - % Asistencia: {$estadisticas['porcentaje_asistencia']}%\n";
echo "   - % Inasistencia: {$estadisticas['porcentaje_inasistencia']}%\n";

// Obtener estado automático
$estadoAutomatico = Cita::determinarEstadoAutomatico($paciente->id);
echo "   - Estado automático: {$estadoAutomatico}\n";

echo "\n✅ Test completado exitosamente!\n";
echo "\n💡 Para probar el nuevo comportamiento:\n";
echo "   1. Como médico, marca una cita como 'Cancelada'\n";
echo "   2. Verifica que aparezca como 'No asistió' en el historial\n";
echo "   3. Observa cómo afecta el porcentaje de asistencia\n";
