<?php

namespace App\Mail;

use App\Models\Clinica;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClinicaActualizada extends Mailable
{
    use Queueable, SerializesModels;

    public $clinica;
    public $cambios;
    public $actualizadaPor;

    /**
     * Create a new message instance.
     */
    public function __construct(Clinica $clinica, array $cambios, $actualizadaPor = null)
    {
        $this->clinica = $clinica;
        $this->cambios = $cambios;
        $this->actualizadaPor = $actualizadaPor;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Información de clínica actualizada - Meditech')
                    ->view('emails.clinica_actualizada');
    }
}
