<?php

namespace App\Events;

use App\Models\Cliente;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClienteEditado implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $cliente;

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    public function broadcastOn()
    {
        return new Channel('clientes');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->cliente->id,
            'nombre' => $this->cliente->nombre,
            'apellido' => $this->cliente->apellido,
        ];
    }
}
