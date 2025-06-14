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
    }

    /**
     * Calcula el porcentaje de asistencia del paciente
     */
    public static function calcularPorcentajeAsistencia($pacienteId)
    {
        $totalCitasPasadas = self::where('paciente_id', $pacienteId)
            ->where('fecha', '<', now()->toDateString())
            ->whereNotNull('asistio')
            ->count();

        if ($totalCitasPasadas == 0) {
            return 100; // Si no tiene historial, considerar 100% por defecto
        }

        $citasAsistidas = self::where('paciente_id', $pacienteId)
            ->where('fecha', '<', now()->toDateString())
            ->where('asistio', true)
            ->count();

        return round(($citasAsistidas / $totalCitasPasadas) * 100, 2);
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
    }

    public function notasMedicas()
    {
        return $this->hasOne(MedicalNote::class, 'cita_id');
    }
}
