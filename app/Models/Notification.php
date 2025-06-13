<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'tipo',
        'titulo',
        'mensaje',
        'leido',
        'silenciado',
        'fecha_programada'
    ];

    protected $casts = [
        'leido' => 'boolean',
        'silenciado' => 'boolean',
        'fecha_programada' => 'datetime'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class);
    }

    public function scopeNoLeidas($query)
    {
        return $query->where('leido', false);
    }

    public function scopeNoSilenciadas($query)
    {
        return $query->where('silenciado', false);
    }
}
