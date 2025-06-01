<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';

    protected $fillable = ['nombre'];

    public $timestamps = false;

    // Relación inversa con usuarios
    public function usuarios()
    {
        return $this->belongsToMany(Usuarios::class, 'usuario_roles', 'rol_id', 'usuario_id');
    }
}

