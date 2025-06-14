<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClinicasEspecialidadesSeeder extends Seeder
{
    public function run(): void
    {
        // Crear clínicas de prueba
        $clinicas = [
            [
                'nombre' => 'Clínica Central',
                'direccion' => 'Av. Principal 123',
                'telefono' => '555-0123',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Clínica Norte',
                'direccion' => 'Calle Norte 456',
                'telefono' => '555-0456',
                'estado' => 'activo',
            ],
        ];

        foreach ($clinicas as $clinica) {
            DB::table('clinicas')->insertOrIgnore($clinica);
        }

        // Crear especialidades médicas
        $especialidades = [
            [
                'especialidad' => 'Medicina General',
                'descripcion' => 'Atención médica general y preventiva',
                'estado' => 'activo',
            ],
            [
                'especialidad' => 'Pediatría',
                'descripcion' => 'Atención médica para niños',
                'estado' => 'activo',
            ],
            [
                'especialidad' => 'Cardiología',
                'descripcion' => 'Especialidad en sistema cardiovascular',
                'estado' => 'activo',
            ],
        ];

        foreach ($especialidades as $especialidad) {
            DB::table('especialidad')->insertOrIgnore($especialidad);
        }

        // Asignar especialidad al médico de prueba
        $medico = \App\Models\Medico::first();
        $especialidadId = DB::table('especialidad')->where('especialidad', 'Medicina General')->first()->id;
        
        if ($medico) {
            DB::table('medico_especialidad')->insertOrIgnore([
                'medico_id' => $medico->id,
                'especialidad_id' => $especialidadId,
            ]);
        }

        // Asignar clínica al médico de prueba
        $clinicaId = DB::table('clinicas')->where('nombre', 'Clínica Central')->first()->id;
        
        if ($medico) {
            DB::table('medico_clinica')->insertOrIgnore([
                'medico_id' => $medico->id,
                'clinica_id' => $clinicaId,
            ]);
        }
    }
}
