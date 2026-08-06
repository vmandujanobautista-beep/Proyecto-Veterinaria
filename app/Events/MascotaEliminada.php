<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MascotaEliminada implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $mascota_id;

    public function __construct($mascota_id)
    {
        $this->mascota_id = $mascota_id;
    }

    public function broadcastOn()
    {
        return new Channel('mascotas');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->mascota_id,
        ];
    }
}
