<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Factories\Factory;

class VentaProductoFactory extends Factory
{
    public function definition(): array
    {
        $producto = Producto::inRandomOrder()->first();
        
        if (!$producto) {
            // Fallback en caso de que no haya productos en la base de datos
            $producto = Producto::factory()->create([
                'nombre' => 'Producto Dummy',
                'precio' => 100,
                'stock' => 100
            ]);
        }

        $cantidad = $this->faker->numberBetween(1, 5);
        $precio = $producto->precio;
        
        return [
            'venta_id' => Venta::factory(),
            'producto_id' => $producto->id,
            'cantidad' => $cantidad,
            'precio_unitario' => $precio,
            'subtotal' => $precio * $cantidad,
        ];
    }
}
