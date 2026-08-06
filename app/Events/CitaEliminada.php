<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CitaEliminada implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $cita_id;

    public function __construct($cita_id)
    {
        $this->cita_id = $cita_id;
    }

    public function broadcastOn()
    {
        return new Channel('citas');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->cita_id,
        ];
    }
}
