<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VentaEliminada implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $venta_id;

    public function __construct($venta_id)
    {
        $this->venta_id = $venta_id;
    }

    public function broadcastOn()
    {
        return new Channel('ventas');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->venta_id,
        ];
    }
}
