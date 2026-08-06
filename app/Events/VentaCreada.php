<?php

namespace App\Events;

use App\Models\Venta;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VentaCreada implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $venta;

    public function __construct(Venta $venta)
    {
        $this->venta = $venta;
    }

    public function broadcastOn()
    {
        return new Channel('ventas');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->venta->id,
            'total' => $this->venta->total,
            'cliente_id' => $this->venta->cliente_id,
        ];
    }
}
