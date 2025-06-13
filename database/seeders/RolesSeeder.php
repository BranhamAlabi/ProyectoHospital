<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['nombre' => 'administrador'],
            ['nombre' => 'moderador'],
            ['nombre' => 'medico'],
            ['nombre' => 'paciente'],
        ];

        foreach ($roles as $rol) {
            Rol::firstOrCreate(['nombre' => $rol['nombre']]);
        }
    }
}
