<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuracion';

    protected $fillable = [
        'clinica_nombre',
        'clinica_logo',
        'clinica_direccion',
        'clinica_telefono',
        'clinica_email',
        'horarios',
        'servicios',
        'metodos_pago',
        'mensaje_confirmacion',
        'mensaje_whatsapp',
    ];

    protected $casts = [
        'horarios'     => 'array',
        'servicios'    => 'array',
        'metodos_pago' => 'array',
    ];

    /**
     * Obtiene la instancia única de configuración, creando una si no existe.
     */
    public static function instancia(): static
    {
        return static::firstOrCreate(
            ['id' => 1],
            ['clinica_nombre' => 'VetCare']
        );
    }
}
