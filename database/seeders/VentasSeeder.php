<?php

namespace Database\Seeders;

use App\Models\Venta;
use App\Models\VentaProducto;
use Illuminate\Database\Seeder;

class VentasSeeder extends Seeder
{
    public function run(): void
    {
        // Generar 50 ventas
        Venta::factory(50)->create()->each(function ($venta) {
            // A cada venta le asignamos entre 1 y 4 productos
            $numProductos = rand(1, 4);
            
            $productos = VentaProducto::factory($numProductos)->create([
                'venta_id' => $venta->id
            ]);
            
            // Actualizamos el total de la venta sumando los subtotales
            $venta->update([
                'total' => $productos->sum('subtotal')
            ]);
        });
    }
}
