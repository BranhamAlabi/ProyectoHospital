<?php

namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CitaModificada extends Mailable
{
    use Queueable, SerializesModels;

    public $cita;
    public $cambios;
    public $modificadaPor;

    /**
     * Create a new message instance.
     */
    public function __construct(Cita $cita, array $cambios, $modificadaPor = null)
    {
        $this->cita = $cita;
        $this->cambios = $cambios;
        $this->modificadaPor = $modificadaPor;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Tu cita médica ha sido modificada - Meditech')
                    ->view('emails.cita_modificada');
    }
}
