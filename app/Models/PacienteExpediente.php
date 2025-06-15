<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PacienteExpediente extends Model
{
    use HasFactory;

    protected $table = 'paciente_expediente';    protected $fillable = [
        'id_paciente',
        'fecha_nacimiento',
        'sexo',
        'direccion',
        'telefono',
        'enfermedades_cronicas',
        'cirugias_previas',
        'alergias',
        'tratamientos_actuales',
        'editado_por'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];    /**
     * Relación con el paciente (usuario)
     */
    public function paciente()
    {
        return $this->belongsTo(Usuarios::class, 'id_paciente');
    }

    /**
     * Relación con el médico que editó el expediente
     */
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'editado_por');
    }

    /**
     * Accessor para formatear el sexo
     */
    public function getSexoFormateadoAttribute()
    {
        return match($this->sexo) {
            'M' => 'Masculino',
            'F' => 'Femenino',
            'Otro' => 'Otro',
            default => 'No especificado'
        };
    }

    /**
     * Accessor para calcular la edad
     */
    public function getEdadAttribute()
    {
        return $this->fecha_nacimiento ? $this->fecha_nacimiento->age : null;
    }
}
