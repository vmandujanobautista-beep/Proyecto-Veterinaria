<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    protected $fillable = [
        'fecha',
        'hora',
        'tipo_servicio',
        'motivo',
        'estado',
        'enviado_email',
        'enviado_whatsapp',
        'cliente_id',
        'mascota_id',
    ];

    protected $casts = [
        'fecha' => 'date',
        'enviado_email' => 'boolean',
        'enviado_whatsapp' => 'boolean',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class);
    }
}
