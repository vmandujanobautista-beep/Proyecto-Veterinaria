<?php
use App\Models\Configuracion;
use App\Models\Cita;
use App\Models\Mascota;
use App\Models\User;
use Carbon\Carbon;

// 1. Update servicios
$config = Configuracion::first();
$servicios = [
    ['nombre' => 'Consulta General', 'precio' => 300],
    ['nombre' => 'Vacunación', 'precio' => 450],
    ['nombre' => 'Baño y Corte', 'precio' => 600],
    ['nombre' => 'Cirugía', 'precio' => 2500],
    ['nombre' => 'Rayos X / Ultrasonido', 'precio' => 1200]
];
$config->servicios = $servicios; // Eloquent cast will encode to JSON
$config->save();

// 2. Get random pets
$mascotas = Mascota::inRandomOrder()->limit(5)->get();
$user = User::first(); // Assuming there's a user to assign user_id
$hoy = Carbon::today()->format('Y-m-d');
$horas = ['09:00', '10:30', '12:00', '15:30', '17:00'];

// 3. Create appointments
foreach ($mascotas as $i => $mascota) {
    $servicio = $servicios[$i];
    
    Cita::create([
        'user_id' => $user->id,
        'cliente_id' => $mascota->cliente_id,
        'mascota_id' => $mascota->id,
        'fecha' => $hoy,
        'hora' => $horas[$i],
        'tipo_servicio' => $servicio['nombre'],
        'precio' => $servicio['precio'],
        'motivo' => 'Motivo autogenerado para demostración de ' . $servicio['nombre'],
        'estado' => 'confirmada', // 'confirmada'
        'enviado_email' => 1,
        'enviado_whatsapp' => 0
    ]);
}

echo "5 citas creadas exitosamente.";
