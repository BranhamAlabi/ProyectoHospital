<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cita;
use App\Models\Usuarios;

class ActualizarEstadosCitas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citas:actualizar-estados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza los estados de las citas basado en el porcentaje de asistencia de los pacientes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando actualización de estados de citas...');

        // Obtener todas las citas futuras que no sean canceladas
        $citas = Cita::where('fecha', '>=', now()->toDateString())
            ->whereIn('estado', ['pendiente', 'aprobada'])
            ->get();

        $actualizadas = 0;

        foreach ($citas as $cita) {
            $estadoActual = $cita->estado;
            $estadoNuevo = Cita::determinarEstadoAutomatico($cita->paciente_id);

            if ($estadoActual !== $estadoNuevo) {
                $cita->update(['estado' => $estadoNuevo]);
                $actualizadas++;
                
                $this->line("Cita ID {$cita->id}: {$estadoActual} → {$estadoNuevo}");
            }
        }

        $this->info("Proceso completado. {$actualizadas} citas actualizadas de {$citas->count()} revisadas.");

        return 0;
    }
}
