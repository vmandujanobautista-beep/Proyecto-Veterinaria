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
     * Singleton en memoria: evita múltiples queries a configuracion
     * durante el mismo request HTTP.
     */
    protected static ?self $cached = null;

    /**
     * Obtiene la instancia única de configuración.
     * La primera llamada hace firstOrCreate; las siguientes son gratuitas.
     */
    public static function instancia(): static
    {
        if (static::$cached === null) {
            static::$cached = static::firstOrCreate(
                ['id' => 1],
                ['clinica_nombre' => 'VetCare']
            );
        }

        return static::$cached;
    }

    /**
     * Invalida el cache en memoria (llamar después de update() para que
     * el siguiente request cargue los valores actualizados).
     */
    public static function invalidarCache(): void
    {
        static::$cached = null;
    }

    // ─────────────────────────────────────────────────────────────
    //  HELPERS — centralizan lógica de defaults para los controllers
    // ─────────────────────────────────────────────────────────────

    /**
     * Devuelve servicios configurados con fallback al servicio por defecto.
     */
    public function getServiciosConDefault(): array
    {
        return !empty($this->servicios)
            ? $this->servicios
            : [['nombre' => 'Consulta General', 'precio' => 500]];
    }

    /**
     * Devuelve horarios configurados con fallback al horario por defecto.
     */
    public function getHorariosConDefault(): array
    {
        return !empty($this->horarios)
            ? $this->horarios
            : [['dia' => 'Lunes - Viernes', 'apertura' => '09:00', 'cierre' => '18:00', 'cerrado' => false]];
    }

    /**
     * Busca el precio de un servicio por nombre.
     * Retorna 0.0 si no se encuentra — reemplaza el foreach duplicado
     * que había en CitaController@store y @update.
     */
    public function lookupPrecioServicio(string $nombreServicio): float
    {
        foreach ($this->getServiciosConDefault() as $servicio) {
            if (($servicio['nombre'] ?? '') === $nombreServicio) {
                return (float) ($servicio['precio'] ?? 0);
            }
        }

        return 0.0;
    }
}

