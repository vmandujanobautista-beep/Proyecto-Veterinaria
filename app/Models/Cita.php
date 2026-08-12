<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasCreator;

class Cita extends Model
{
    use HasFactory, HasCreator;
    // Los servicios y precios ahora se gestionan dinámicamente desde el modelo Configuracion

    protected $fillable = [
        'fecha',
        'hora',
        'tipo_servicio',
        'precio',
        'motivo',
        'estado',
        'enviado_email',
        'enviado_whatsapp',
        'cliente_id',
        'mascota_id',
        'user_id',
    ];

    protected $casts = [
        'fecha' => 'date',
        'enviado_email' => 'boolean',
        'enviado_whatsapp' => 'boolean',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class)->withoutGlobalScope('user_id');
    }

    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class)->withoutGlobalScope('user_id');
    }

    public function confirmaciones(): HasMany
    {
        return $this->hasMany(CitaConfirmacion::class);
    }
}
