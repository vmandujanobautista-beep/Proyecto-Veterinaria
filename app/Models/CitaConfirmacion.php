<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaConfirmacion extends Model
{
    use HasFactory;

    protected $table = 'cita_confirmaciones';

    protected $fillable = [
        'cita_id',
        'canal',
        'destinatario',
        'estado',
        'mensaje_error',
        'provider_message_id',
        'fecha_envio',
    ];

    protected $casts = [
        'fecha_envio' => 'datetime',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }
}
