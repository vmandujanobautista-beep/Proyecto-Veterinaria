<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('clientes', function ($user) {
    return true; // Todos pueden escuchar (o puedes restringirlo)
});

Broadcast::channel('mascotas', function ($user) {
    return true;
});

Broadcast::channel('citas', function ($user) {
    return true;
});

Broadcast::channel('ventas', function ($user) {
    return true;
});
