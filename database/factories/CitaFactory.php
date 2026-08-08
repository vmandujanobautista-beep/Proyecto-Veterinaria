<?php

namespace Database\Factories;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Mascota;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cita>
 */
class CitaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $servicios = array_keys(Cita::SERVICIOS_PRECIOS);
        $tipo_servicio = $this->faker->randomElement($servicios);
        $precio = Cita::SERVICIOS_PRECIOS[$tipo_servicio];

        // Ensure there's a client and mascot
        $cliente = Cliente::inRandomOrder()->first() ?? Cliente::factory()->create();
        $mascota = Mascota::where('cliente_id', $cliente->id)->inRandomOrder()->first() ?? Mascota::factory()->create(['cliente_id' => $cliente->id]);

        return [
            'fecha' => $this->faker->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
            'hora' => $this->faker->time('H:i'),
            'tipo_servicio' => $tipo_servicio,
            'precio' => $precio,
            'motivo' => $this->faker->sentence(),
            'estado' => $this->faker->randomElement(['pendiente', 'confirmada', 'completada', 'cancelada']),
            'enviado_email' => $this->faker->boolean(70),
            'enviado_whatsapp' => $this->faker->boolean(70),
            'cliente_id' => $cliente->id,
            'mascota_id' => $mascota->id,
        ];
    }
}
