<?php
/**
 * Script para probar la funcionalidad de cambio de estado de citas
 */

require_once 'vendor/autoload.php';

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Cita;
use App\Models\DoctorSchedule;
use App\Models\Medico;

echo "=== PRUEBA DE SIMULACIÓN ===\n\n";

// Obtener un médico con horarios y citas
$medico = Medico::with(['usuario'])->first();
if (!$medico) {
    echo "No hay médicos en el sistema\n";
    exit;
}

echo "Médico: {$medico->usuario->nombre} (ID: {$medico->id})\n\n";

// Obtener horarios existentes
$horariosExistentes = DoctorSchedule::where('medico_id', $medico->id)->get();
echo "Horarios existentes:\n";
foreach ($horariosExistentes as $h) {
    echo "- {$h->dia_semana}, {$h->hora_inicio} - {$h->hora_fin}, Clínica: {$h->clinica_id}\n";
}

// Obtener citas aprobadas
$citasAprobadas = Cita::where('medico_id', $medico->id)
    ->where('estado', 'aprobada')
    ->where('fecha', '>=', now()->toDateString())
    ->get();

echo "\nCitas aprobadas futuras:\n";
foreach ($citasAprobadas as $c) {
    $fecha = \Carbon\Carbon::parse($c->fecha);
    $diaSemana = $fecha->locale('es')->isoFormat('dddd');
    echo "- ID: {$c->id}, Fecha: {$c->fecha} ({$diaSemana}), Hora: {$c->hora}, Clínica: {$c->clinica_id}\n";
}

echo "\n=== SIMULANDO ELIMINACIÓN DE HORARIO DOMINGO ===\n";

// Simular nuevos horarios (eliminando domingo)
$nuevosHorarios = [];
foreach ($horariosExistentes as $h) {
    if ($h->dia_semana !== 'Domingo') {
        $nuevosHorarios[] = [
            'dia_semana' => $h->dia_semana,
            'clinica_id' => $h->clinica_id,
            'hora_inicio' => substr($h->hora_inicio, 0, 5),
            'hora_fin' => substr($h->hora_fin, 0, 5),
            'pacientes_por_hora' => $h->pacientes_por_hora
        ];
    }
}

echo "Nuevos horarios (sin domingo):\n";
foreach ($nuevosHorarios as $nh) {
    echo "- {$nh['dia_semana']}, {$nh['hora_inicio']} - {$nh['hora_fin']}, Clínica: {$nh['clinica_id']}\n";
}

// Instanciar el controller para usar los métodos privados
$controller = new \App\Http\Controllers\MedicosController();

// Usar reflexión para acceder a los métodos privados
$reflection = new ReflectionClass($controller);

$metodoIdentificar = $reflection->getMethod('identificarCitasAfectadasPorCambioHorario');
$metodoIdentificar->setAccessible(true);

$metodoBuscar = $reflection->getMethod('buscarCitasEnHorario');
$metodoBuscar->setAccessible(true);

echo "\n=== EJECUTANDO LÓGICA DE IDENTIFICACIÓN ===\n";

$citasAfectadas = $metodoIdentificar->invoke($controller, $medico->id, $horariosExistentes, $nuevosHorarios);

echo "Citas que deberían cambiar a pendiente_reprogramacion: " . implode(', ', $citasAfectadas) . "\n";

echo "\n=== REVISA LOS LOGS EN storage/logs/laravel.log ===\n";
