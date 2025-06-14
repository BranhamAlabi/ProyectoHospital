<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PacienteExpediente;
use App\Models\User;
use App\Models\Medico;

class PacienteExpedienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunos pacientes y médicos para el seeder
        $pacientes = User::where('rol_id', 3)->take(5)->get(); // Asumiendo que rol_id 3 es paciente
        $medicos = Medico::take(3)->get();

        if ($pacientes->isEmpty() || $medicos->isEmpty()) {
            $this->command->info('No hay pacientes o médicos suficientes para crear expedientes de prueba.');
            return;
        }

        foreach ($pacientes as $paciente) {
            PacienteExpediente::create([
                'id_paciente' => $paciente->id,
                'nombre_completo' => $paciente->nombre . ' ' . $paciente->apellido,
                'fecha_nacimiento' => fake()->date('Y-m-d', '2000-01-01'),
                'sexo' => fake()->randomElement(['M', 'F']),
                'direccion' => fake()->address(),
                'telefono' => fake()->phoneNumber(),
                'enfermedades_cronicas' => fake()->optional()->randomElement([
                    'Diabetes tipo 2',
                    'Hipertensión arterial',
                    'Asma bronquial',
                    'Artritis reumatoide',
                    null
                ]),
                'cirugias_previas' => fake()->optional()->randomElement([
                    'Apendicectomía (2019)',
                    'Cirugía de vesícula biliar (2020)',
                    'Cesárea (2021)',
                    null
                ]),
                'alergias' => fake()->optional()->randomElement([
                    'Penicilina',
                    'Polen',
                    'Mariscos',
                    'Ácaros del polvo',
                    null
                ]),
                'tratamientos_actuales' => fake()->optional()->randomElement([
                    'Metformina 850mg - 1 tableta cada 12 horas',
                    'Losartán 50mg - 1 tableta diaria',
                    'Salbutamol inhalador - según necesidad',
                    null
                ]),
                'editado_por' => $medicos->random()->id
            ]);
        }

        $this->command->info('Expedientes personales de pacientes creados exitosamente.');
    }
}
