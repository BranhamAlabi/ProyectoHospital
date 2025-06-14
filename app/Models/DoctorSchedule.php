<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    use HasFactory;    protected $fillable = [
        'medico_id',
        'clinica_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'pacientes_por_hora',
        'activo'
    ];

    protected $casts = [
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
        'activo' => 'boolean'
    ];    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    public function clinica()
    {
        return $this->belongsTo(Clinica::class, 'clinica_id');
    }
}
