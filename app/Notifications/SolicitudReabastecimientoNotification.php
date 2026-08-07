<?php

namespace App\Notifications;

use App\Mail\SolicitudReabastecimiento;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SolicitudReabastecimientoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $producto;
    public $solicitante;

    /**
     * Create a new notification instance.
     */
    public function __construct(Producto $producto, User $solicitante)
    {
        $this->producto = $producto;
        $this->solicitante = $solicitante;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        // Reutilizamos el Mailable que ya tiene todo el diseño y lógica de colores.
        return (new SolicitudReabastecimiento($this->producto, $this->solicitante))
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $urgencia = $this->producto->stock === 0 
            ? 'agotado' 
            : ($this->producto->stock <= 4 ? 'últimas_unidades' : 'stock_bajo');

        return [
            'producto_id' => $this->producto->id,
            'producto_nombre' => $this->producto->nombre,
            'producto_codigo' => $this->producto->codigo,
            'stock_actual' => $this->producto->stock,
            'urgencia' => $urgencia,
            'solicitante_id' => $this->solicitante->id,
            'solicitante_nombre' => $this->solicitante->name,
            'mensaje' => "Solicitud de reabastecimiento: {$this->producto->nombre} (Stock: {$this->producto->stock})",
        ];
    }
}
