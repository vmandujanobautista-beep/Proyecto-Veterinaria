<?php

namespace App\Events;

use App\Models\Mascota;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MascotaEditada implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $mascota;

    public function __construct(Mascota $mascota)
    {
        $this->mascota = $mascota;
    }

    public function broadcastOn()
    {
        return new Channel('mascotas');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->mascota->id,
            'nombre' => $this->mascota->nombre,
        ];
    }
}
