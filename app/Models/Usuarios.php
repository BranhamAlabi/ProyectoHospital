<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuarios extends Authenticatable
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'correo',
        'contrasena',
        'estado',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    // Override the email field name for authentication
    public function getEmailAttribute()
    {
        return $this->correo;
    }

    // Override the password field name for authentication
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    // Tell Laravel to use 'correo' as the username field
    public function username()
    {
        return 'correo';
    }

    // Relación muchos a muchos con roles
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'usuario_roles', 'usuario_id', 'rol_id');
    }

    // Relación con citas como paciente
    public function citasPaciente()
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

    // Relación con citas como médico
    public function citasMedico()
    {
        return $this->hasMany(Cita::class, 'medico_id');
    }

    // Relación con el perfil de médico
    public function medico()
    {
        return $this->hasOne(Medico::class, 'id');
    }
}


