<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // si usas auth

class Usuarios extends Authenticatable
{
    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'correo',
        'contrasena',
        'estado',
    ];

    protected $hidden = [
        'contrasena',
    ];

    // Relación muchos a muchos con roles
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'usuario_roles', 'usuario_id', 'rol_id');
    }
}

