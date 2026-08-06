<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClienteEliminado implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $cliente_id;

    public function __construct($cliente_id)
    {
        $this->cliente_id = $cliente_id;
    }

    public function broadcastOn()
    {
        return new Channel('clientes');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->cliente_id,
        ];
    }
}
