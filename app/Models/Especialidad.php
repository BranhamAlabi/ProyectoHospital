<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidad';

    protected $fillable = [
        'especialidad',
    ];

    public $timestamps = false;

    public function medicos()
    {
        return $this->hasMany(Medico::class, 'esp_id');
    }
}
