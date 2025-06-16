<?php

namespace App\Mail;

use App\Models\Clinica;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClinicaCreada extends Mailable
{
    use Queueable, SerializesModels;

    public $clinica;
    public $creadaPor;

    /**
     * Create a new message instance.
     */
    public function __construct(Clinica $clinica, $creadaPor = null)
    {
        $this->clinica = $clinica;
        $this->creadaPor = $creadaPor;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Nueva clínica registrada en Meditech')
                    ->view('emails.clinica_creada');
    }
}
