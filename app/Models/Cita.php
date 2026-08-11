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
    public const SERVICIOS_PRECIOS = [
        'Consulta General' => 500,
        'Vacunación' => 350,
        'Desparasitación' => 200,
        'Baño y Corte' => 450,
        'Esterilización/Castración' => 1200,
        'Cirugía' => 3000,
        'Laboratorio' => 800,
        'Rayos X / Ultrasonido' => 950,
        'Chequeo General' => 600,
        'Urgencias' => 1500,
    ];

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
