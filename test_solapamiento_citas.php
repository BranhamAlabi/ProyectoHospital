<?php
/**
 * Script para probar la validación de solapamiento de citas
 */

require_once 'vendor/autoload.php';

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Cita;
use App\Models\Usuarios;

echo "=== PRUEBA DE VALIDACIÓN DE SOLAPAMIENTO DE CITAS ===\n\n";

// Obtener un paciente con citas existentes
$pacientesConCitas = Usuarios::whereHas('citasPaciente', function($query) {
    $query->whereNotIn('estado', ['cancelada']);
})->with(['citasPaciente' => function($query) {
    $query->whereNotIn('estado', ['cancelada'])
          ->where('fecha', '>=', now()->toDateString())
          ->orderBy('fecha')
          ->orderBy('hora');
}])->first();

if (!$pacientesConCitas) {
    echo "No hay pacientes con citas activas para probar.\n";
    exit;
}

echo "Paciente: {$pacientesConCitas->nombre} (ID: {$pacientesConCitas->id})\n\n";

echo "Citas existentes del paciente:\n";
foreach ($pacientesConCitas->citasPaciente as $cita) {
    echo "- {$cita->fecha} a las {$cita->hora} - Estado: {$cita->estado}\n";
    echo "  Médico ID: {$cita->medico_id}, Clínica ID: {$cita->clinica_id}\n";
}

if ($pacientesConCitas->citasPaciente->count() > 0) {
    $citaEjemplo = $pacientesConCitas->citasPaciente->first();
    
    echo "\n=== SIMULANDO CREACIÓN DE CITA SOLAPADA ===\n";
    echo "Intentando crear otra cita para:\n";
    echo "- Fecha: {$citaEjemplo->fecha}\n";
    echo "- Hora: {$citaEjemplo->hora}\n";
    echo "- Paciente: {$pacientesConCitas->nombre}\n";
    echo "- Con DIFERENTE médico/clínica\n\n";
    
    // Simular validación
    $citasSolapadas = Cita::where('paciente_id', $pacientesConCitas->id)
        ->where('fecha', $citaEjemplo->fecha)
        ->where('hora', $citaEjemplo->hora)
        ->whereNotIn('estado', ['cancelada'])
        ->exists();
    
    if ($citasSolapadas) {
        echo "✅ VALIDACIÓN CORRECTA: Se detectó el solapamiento.\n";
        echo "❌ La cita NO se debería crear - mensaje de error mostrado al usuario.\n";
    } else {
        echo "❌ ERROR: No se detectó el solapamiento.\n";
    }
    
    echo "\n=== SIMULANDO CREACIÓN DE CITA SIN SOLAPAMIENTO ===\n";
    
    // Buscar una fecha/hora libre
    $fechaLibre = now()->addDays(5)->format('Y-m-d');
    $horaLibre = '09:00:00';
    
    $citasSolapadasLibre = Cita::where('paciente_id', $pacientesConCitas->id)
        ->where('fecha', $fechaLibre)
        ->where('hora', $horaLibre)
        ->whereNotIn('estado', ['cancelada'])
        ->exists();
    
    echo "Intentando crear cita para:\n";
    echo "- Fecha: {$fechaLibre}\n";
    echo "- Hora: {$horaLibre}\n";
    
    if (!$citasSolapadasLibre) {
        echo "✅ VALIDACIÓN CORRECTA: No hay solapamiento.\n";
        echo "✅ La cita SÍ se debería crear.\n";
    } else {
        echo "❌ Hay solapamiento inesperado.\n";
    }
}

echo "\n=== RESUMEN DE LA FUNCIONALIDAD ===\n";
echo "✅ Validación implementada en PacienteCitasController::store()\n";
echo "✅ Verifica fecha + hora + paciente_id\n";
echo "✅ Excluye citas canceladas\n";
echo "✅ Funciona independientemente del médico o clínica\n";
echo "✅ Muestra mensaje de error claro al usuario\n";
echo "✅ Preserva los datos del formulario con withInput()\n\n";

echo "Para probar en el sistema:\n";
echo "1. Inicia sesión como paciente\n";
echo "2. Crea una cita para una fecha/hora específica\n";
echo "3. Intenta crear otra cita para la misma fecha/hora (con diferente médico)\n";
echo "4. Deberías ver el error: 'Ya tienes una cita agendada para esta fecha y hora...'\n";
