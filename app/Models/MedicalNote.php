<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'cita_id',
        'medico_id',
        'paciente_id',
        'diagnostico',
        'tratamiento',
        'observaciones',
        'notas_adicionales'
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    public function paciente()
    {
        return $this->belongsTo(Usuarios::class, 'paciente_id');
    }
}
