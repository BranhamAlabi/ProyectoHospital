<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuarios;
use App\Models\Rol;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
        ]);

        // Crear usuario médico de prueba
        $medico = Usuarios::firstOrCreate(
            ['correo' => 'medico@test.com'],
            [
                'nombre' => 'Dr. Juan Pérez',
                'contrasena' => Hash::make('password123'),
                'estado' => 'activo',
            ]
        );

        // Asignar rol de médico
        $medicoRol = Rol::where('nombre', 'medico')->first();
        if ($medicoRol && !$medico->roles()->where('rol_id', $medicoRol->id)->exists()) {
            $medico->roles()->attach($medicoRol->id);
        }

        // Crear registro en la tabla medicos si no existe
        if (!\App\Models\Medico::where('id', $medico->id)->exists()) {
            \App\Models\Medico::create([
                'id' => $medico->id
            ]);
        }

        // Crear citas de prueba para el médico
        $paciente = \App\Models\Usuarios::firstOrCreate(
            ['correo' => 'paciente@test.com'],
            [
                'nombre' => 'Paciente Test',
                'contrasena' => bcrypt('password123'),
                'estado' => 'activo',
            ]
        );

        \App\Models\Cita::firstOrCreate(
            [
                'medico_id' => $medico->id,
                'paciente_id' => $paciente->id,
                'fecha' => now()->addDays(1)->toDateString(),
                'hora' => '10:00:00',
            ],
            [
                'motivo' => 'Consulta general',
                'estado' => 'pendiente',
                'created_by' => $medico->id,
                'updated_by' => $medico->id,
            ]
        );
    }
}
