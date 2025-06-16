<?php

namespace App\Mail;

use App\Models\Medico;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MedicoAsignado extends Mailable
{
    use Queueable, SerializesModels;

    public $medico;
    public $asignadoPor;

    /**
     * Create a new message instance.
     */
    public function __construct(Medico $medico, $asignadoPor = null)
    {
        $this->medico = $medico;
        $this->asignadoPor = $asignadoPor;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Has sido asignado como médico en Meditech')
                    ->view('emails.medico_asignado');
    }
}
