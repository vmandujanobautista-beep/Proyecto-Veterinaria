<?php

namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CitaConfirmacionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * La cita asociada al correo de confirmación.
     */
    public Cita $cita;

    /**
     * Crea una nueva instancia del mailable.
     */
    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    /**
     * Define el sobre del correo (asunto, remitente, destinatario).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de Cita - Veterinaria',
        );
    }

    /**
     * Define el contenido del correo.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.cita-confirmacion',
        );
    }

    /**
     * Archivos adjuntos del correo.
     */
    public function attachments(): array
    {
        return [];
    }
}
