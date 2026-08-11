<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasCreator;

class Mascota extends Model
{
    use HasFactory, HasCreator;

    protected $fillable = [
        'nombre',
        'especie',
        'raza',
        'sexo',
        'peso',
        'fecha_nacimiento',
        'nota_medica',
        'foto',
        'color_pelaje',
        'cliente_id',
        'user_id',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'peso' => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        // Sin scope de user_id para que la relación funcione correctamente
        // (el cliente pertenece al mismo usuario, pero el scope no debe filtrar relaciones)
        return $this->belongsTo(Cliente::class)->withoutGlobalScope('user_id');
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }
}
