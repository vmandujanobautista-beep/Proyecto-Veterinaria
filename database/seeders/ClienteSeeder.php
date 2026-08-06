<?php
namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Mascota;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        // Crear 100 clientes
        Cliente::factory()->count(100)->create()->each(function ($cliente) {
            // Cada cliente tiene entre 0 y 5 mascotas (aleatorio)
            $numMascotas = rand(0, 5);
            
            for ($i = 0; $i < $numMascotas; $i++) {
                Mascota::factory()->create([
                    'cliente_id' => $cliente->id,
                ]);
            }
        });

        $this->command->info('✅ 100 clientes creados con sus mascotas exitosamente.');
    }
}
