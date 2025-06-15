<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';    protected $fillable = [
        'paciente_id',
        'medico_id',
        'clinica_id',
        'doctor_schedule_id',
        'fecha',
        'hora',
        'motivo',
        'estado',
        'asistio',
        'comentarios',
        'created_by',
        'updated_by',
    ];

    public function clinica()
    {
        return $this->belongsTo(Clinica::class, 'clinica_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Usuarios::class, 'paciente_id');
    }    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    public function creador()
    {
        return $this->belongsTo(Usuarios::class, 'created_by');
    }

    public function actualizador()
    {
        return $this->belongsTo(Usuarios::class, 'updated_by');
    }

    public function doctorSchedule()
    {
        return $this->belongsTo(DoctorSchedule::class, 'doctor_schedule_id');
    }    /**
     * Calcula el porcentaje de asistencia del paciente
     * 
     * Incluye en el cálculo:
     * - Citas confirmadas: Se consideran como asistencia (independientemente de la fecha)
     * - Citas canceladas: Se consideran como inasistencia (independientemente de la fecha)
     * - Citas con asistio definido: Se usan según su valor
     * - Citas pendientes futuras: No se incluyen en el cálculo
     */
    public static function calcularPorcentajeAsistencia($pacienteId)
    {
        // Contar todas las citas que deben incluirse en el cálculo
        $totalCitasEvaluadas = self::where('paciente_id', $pacienteId)
            ->where(function($query) {
                $query->where(function($q) {
                    // Citas pasadas/hoy con asistio definido
                    $q->where('fecha', '<=', now()->toDateString())
                      ->whereNotNull('asistio');
                })->orWhere(function($q) {
                    // Citas confirmadas (futuras o pasadas)
                    $q->whereRaw('LOWER(TRIM(estado)) = ?', ['confirmada']);
                })->orWhere(function($q) {
                    // Citas canceladas (futuras o pasadas) - cuentan como inasistencia
                    $q->whereRaw('LOWER(TRIM(estado)) = ?', ['cancelada']);
                });
            })
            ->count();

        if ($totalCitasEvaluadas == 0) {
            // Si no tiene historial evaluado, verificar si tiene citas futuras pendientes
            $citasPendientesFuturas = self::where('paciente_id', $pacienteId)
                ->where('fecha', '>', now()->toDateString())
                ->whereNotIn('estado', ['cancelada', 'Cancelada', 'confirmada', 'Confirmada'])
                ->count();
            
            if ($citasPendientesFuturas > 0) {
                return null; // Paciente nuevo con solo citas pendientes - no mostrar porcentaje
            }
            
            return 100; // Si no tiene citas, considerar 100% por defecto
        }

        // Contar citas que se consideran como asistencia
        $citasAsistidas = self::where('paciente_id', $pacienteId)
            ->where(function($query) {
                $query->where(function($q) {
                    // Citas pasadas/hoy marcadas como asistidas
                    $q->where('fecha', '<=', now()->toDateString())
                      ->where('asistio', true);
                })->orWhere(function($q) {
                    // Citas confirmadas se consideran como asistidas
                    $q->whereRaw('LOWER(TRIM(estado)) = ?', ['confirmada']);
                });
            })
            ->count();

        return round(($citasAsistidas / $totalCitasEvaluadas) * 100, 2);
    }

    /**
     * Determina el estado automático basado en el porcentaje de asistencia
     */
    public static function determinarEstadoAutomatico($pacienteId)
    {
        $porcentajeAsistencia = self::calcularPorcentajeAsistencia($pacienteId);

        if ($porcentajeAsistencia >= 80) {
            return 'aprobada';
        } elseif ($porcentajeAsistencia >= 25) {
            return 'pendiente';
        } else {
            return 'cancelada';
        }
    }

    /**
     * Scope para filtrar citas aprobadas
     */
    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'aprobada');
    }

    /**
     * Scope para filtrar citas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para filtrar citas canceladas
     */
    public function scopeCanceladas($query)
    {
        return $query->where('estado', 'cancelada');
    }    public function medicalNotes()
    {
        return $this->hasMany(MedicalNote::class, 'cita_id');
    }

    public function expedienteMedico()
    {
        return $this->hasOne(ExpedienteMedico::class, 'cita_id');
    }

    /**
     * Obtiene estadísticas detalladas de asistencia del paciente
     */
    public static function obtenerEstadisticasAsistencia($pacienteId)
    {
        $citasPasadas = self::where('paciente_id', $pacienteId)
            ->where('fecha', '<', now()->toDateString())
            ->whereNotNull('asistio');

        $totalEvaluadas = $citasPasadas->count();
        $asistencias = $citasPasadas->where('asistio', true)->count();
        $inasistencias = $citasPasadas->where('asistio', false)->count();
        
        $porcentajeAsistencia = $totalEvaluadas > 0 
            ? round(($asistencias / $totalEvaluadas) * 100, 2)
            : 100;

        return [
            'total_evaluadas' => $totalEvaluadas,
            'asistencias' => $asistencias,
            'inasistencias' => $inasistencias,
            'porcentaje_asistencia' => $porcentajeAsistencia,
            'porcentaje_inasistencia' => $totalEvaluadas > 0 
                ? round(($inasistencias / $totalEvaluadas) * 100, 2) 
                : 0
        ];
    }
}
