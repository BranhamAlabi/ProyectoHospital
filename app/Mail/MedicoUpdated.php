<?php

namespace App\Mail;

use App\Models\Medico;
use App\Models\Usuarios;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MedicoUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $medico;
    public $usuario;

    /**
     * Create a new message instance.
     *
     * @param Medico $medico
     * @param Usuarios $usuario
     */
    public function __construct(Medico $medico, Usuarios $usuario)
    {
        $this->medico = $medico;
        $this->usuario = $usuario;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Actualización de información de médico')
                    ->view('emails.medico_updated');
    }
}
