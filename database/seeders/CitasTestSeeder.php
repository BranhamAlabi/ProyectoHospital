<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitasTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el primer paciente y médico disponibles
        $paciente = \App\Models\Usuarios::whereHas('roles', function($q) {
            $q->where('nombre', 'paciente');
        })->first();

        $medico = \App\Models\Medico::with('usuario')->first();
        $clinica = \App\Models\Clinica::first();

        if (!$paciente || !$medico || !$clinica) {
            $this->command->info('No hay datos suficientes para crear citas de prueba.');
            return;
        }

        // Crear citas pasadas para simular historial (algunas asistió, otras no)
        $citasPasadas = [
            ['fecha' => \Carbon\Carbon::now()->subDays(30), 'asistio' => true],
            ['fecha' => \Carbon\Carbon::now()->subDays(25), 'asistio' => true],
            ['fecha' => \Carbon\Carbon::now()->subDays(20), 'asistio' => false],
            ['fecha' => \Carbon\Carbon::now()->subDays(15), 'asistio' => true],
            ['fecha' => \Carbon\Carbon::now()->subDays(10), 'asistio' => true],
            ['fecha' => \Carbon\Carbon::now()->subDays(5), 'asistio' => true],
        ];

        foreach ($citasPasadas as $citaData) {
            \App\Models\Cita::create([
                'paciente_id' => $paciente->id,
                'medico_id' => $medico->id,
                'clinica_id' => $clinica->id,
                'fecha' => $citaData['fecha']->toDateString(),
                'hora' => '10:00',
                'motivo' => 'Consulta de control',
                'estado' => 'aprobada',
                'asistio' => $citaData['asistio'],
                'created_by' => $paciente->id,
            ]);
        }

        // Crear citas futuras para probar el sistema automático
        $citasFuturas = [
            ['fecha' => \Carbon\Carbon::now()->addDays(1), 'motivo' => 'Consulta general'],
            ['fecha' => \Carbon\Carbon::now()->addDays(3), 'motivo' => 'Seguimiento'],
            ['fecha' => \Carbon\Carbon::now()->addDays(7), 'motivo' => 'Control'],
        ];

        foreach ($citasFuturas as $citaData) {
            $estadoAutomatico = \App\Models\Cita::determinarEstadoAutomatico($paciente->id);
            
            \App\Models\Cita::create([
                'paciente_id' => $paciente->id,
                'medico_id' => $medico->id,
                'clinica_id' => $clinica->id,
                'fecha' => $citaData['fecha']->toDateString(),
                'hora' => '14:00',
                'motivo' => $citaData['motivo'],
                'estado' => $estadoAutomatico,
                'created_by' => $paciente->id,
            ]);
        }

        $porcentajeAsistencia = \App\Models\Cita::calcularPorcentajeAsistencia($paciente->id);
        
        $this->command->info("Citas de prueba creadas para el paciente: {$paciente->nombre}");
        $this->command->info("Porcentaje de asistencia: {$porcentajeAsistencia}%");
        $this->command->info("Estado automático para nuevas citas: " . \App\Models\Cita::determinarEstadoAutomatico($paciente->id));
    }
}
