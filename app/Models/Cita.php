<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'paciente_id',
        'medico_id',
        'fecha',
        'hora',
        'motivo',
        'estado',
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
    }

    public function medico()
    {
        return $this->belongsTo(Usuarios::class, 'medico_id');
    }

    public function creador()
    {
        return $this->belongsTo(Usuarios::class, 'created_by');
    }

    public function actualizador()
    {
        return $this->belongsTo(Usuarios::class, 'updated_by');
    }

    public function notasMedicas()
    {
        return $this->hasOne(MedicalNote::class, 'cita_id');
    }
}
