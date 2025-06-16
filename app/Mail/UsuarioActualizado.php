<?php

namespace App\Mail;

use App\Models\Usuarios;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UsuarioActualizado extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $cambios;
    public $actualizadoPor;

    /**
     * Create a new message instance.
     */
    public function __construct(Usuarios $usuario, array $cambios, $actualizadoPor = null)
    {
        $this->usuario = $usuario;
        $this->cambios = $cambios;
        $this->actualizadoPor = $actualizadoPor;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Notificación de cambios en tu cuenta - Meditech')
                    ->view('emails.usuario_actualizado');
    }
}
