<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',          // campo original — se conserva para compatibilidad
        'apellido_paterno',
        'apellido_materno',
        'email',
        'telefono',
        'codigo_pais',
        'direccion',
        'codigo_postal',
        'foto',
        'estado',
    ];

    protected $casts = [
        'estado' => 'string',
    ];

    /**
     * Nombre completo del cliente (nombre + apellido_paterno + apellido_materno).
     */
    public function getNombreCompletoAttribute(): string
    {
        $partes = array_filter([
            $this->nombre,
            $this->apellido_paterno ?? $this->apellido,
            $this->apellido_materno,
        ]);
        return implode(' ', $partes);
    }

    public function mascotas(): HasMany
    {
        return $this->hasMany(Mascota::class);
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
