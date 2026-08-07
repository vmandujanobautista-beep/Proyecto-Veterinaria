<?php

namespace App\Mail;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SolicitudReabastecimiento extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Producto que requiere reabastecimiento.
     */
    public Producto $producto;

    /**
     * Usuario que realiza la solicitud.
     */
    public User $solicitante;

    /**
     * Crea una nueva instancia del mailable.
     */
    public function __construct(Producto $producto, User $solicitante)
    {
        $this->producto   = $producto;
        $this->solicitante = $solicitante;
    }

    /**
     * Define el asunto del correo.
     */
    public function envelope(): Envelope
    {
        $urgencia = $this->producto->stock === 0
            ? '🔴 URGENTE - '
            : ($this->producto->stock <= 4 ? '⚠️ URGENTE - ' : '📦 ');

        return new Envelope(
            subject: "{$urgencia}Solicitud de reabastecimiento: {$this->producto->nombre}",
        );
    }

    /**
     * Define el contenido del correo.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.solicitud-reabastecimiento',
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
