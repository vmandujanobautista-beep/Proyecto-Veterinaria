<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VentaFactory extends Factory
{
    public function definition(): array
    {
        // 50% de probabilidad de ser Venta Rápida (sin cliente ni mascota)
        $isVentaRapida = $this->faker->boolean();

        return [
            'cliente_id' => $isVentaRapida ? null : (Cliente::inRandomOrder()->first()->id ?? null),
            'mascota_id' => $isVentaRapida ? null : (Mascota::inRandomOrder()->first()->id ?? null),
            'user_id' => User::inRandomOrder()->first()->id ?? 1,
            'metodo_pago' => $this->faker->randomElement(['Efectivo', 'Tarjeta', 'Transferencia']),
            'estado' => $this->faker->randomElement(['pagada', 'pagada', 'pagada', 'pendiente', 'cancelada']),
            'total' => 0, // Se calcula en el seeder después de agregar los productos
        ];
    }
}
