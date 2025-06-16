<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Medico;

class MedicoActualizado extends Mailable
{
    use Queueable, SerializesModels;

    public $medico;
    public $cambios;
    public $actualizadoPor;

    /**
     * Create a new message instance.
     */
    public function __construct(Medico $medico, array $cambios, string $actualizadoPor)
    {
        $this->medico = $medico;
        $this->cambios = $cambios;
        $this->actualizadoPor = $actualizadoPor;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Tu información como médico ha sido actualizada - ' . config('app.name'))
                    ->view('emails.medico_actualizado')
                    ->with([
                        'medico' => $this->medico,
                        'cambios' => $this->cambios,
                        'actualizadoPor' => $this->actualizadoPor,
                    ]);
    }
}
