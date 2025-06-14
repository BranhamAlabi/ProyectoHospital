<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpedienteMedico extends Model
{
    use HasFactory;

    protected $table = 'expedientes_medicos';

    protected $fillable = [
        'paciente_id',
        'medico_id',
        'cita_id',
        'notas',
        'fecha_registro'
    ];

    // Relación con el paciente
    public function paciente()
    {
        return $this->belongsTo(Usuarios::class, 'paciente_id');
    }

    // Relación con el médico
    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    // Relación con la cita
    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }
}
