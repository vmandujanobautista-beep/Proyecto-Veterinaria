<?php

namespace App\Events;

use App\Models\Cita;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CitaCreada implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $cita;

    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    public function broadcastOn()
    {
        return new Channel('citas');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->cita->id,
            'motivo' => $this->cita->motivo,
            'fecha' => $this->cita->fecha,
            'hora' => $this->cita->hora,
            'estado' => $this->cita->estado,
        ];
    }
}
