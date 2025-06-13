<?php

namespace App\Mail;

use App\Models\Cita;
use App\Models\Usuarios;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CitaUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $cita;
    public $updatedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(Cita $cita, Usuarios $updatedBy)
    {
        $this->cita = $cita;
        $this->updatedBy = $updatedBy;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Actualización de cita médica')
                    ->view('emails.cita_updated');
    }
}
