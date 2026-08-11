<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion', function (Blueprint $table) {
            $table->id();
            $table->string('clinica_nombre')->default('VetCare');
            $table->string('clinica_logo')->nullable();
            $table->string('clinica_direccion')->nullable();
            $table->string('clinica_telefono')->nullable();
            $table->string('clinica_email')->nullable();
            $table->text('horarios')->nullable();   // JSON: [{dia, apertura, cierre}]
            $table->text('servicios')->nullable();  // JSON: [{nombre, precio}]
            $table->text('metodos_pago')->nullable(); // JSON: ['efectivo','tarjeta','transferencia']
            $table->text('mensaje_confirmacion')->nullable();
            $table->text('mensaje_whatsapp')->nullable();
            $table->timestamps();
        });

        // Insertar registro inicial
        DB::table('configuracion')->insert([
            'clinica_nombre'      => 'VetCare',
            'clinica_direccion'   => null,
            'clinica_telefono'    => null,
            'clinica_email'       => null,
            'horarios'            => json_encode([
                ['dia' => 'Lunes - Viernes', 'apertura' => '08:00', 'cierre' => '18:00'],
                ['dia' => 'Sábado',          'apertura' => '09:00', 'cierre' => '14:00'],
                ['dia' => 'Domingo',         'apertura' => null,    'cierre' => null, 'cerrado' => true],
            ]),
            'servicios'           => json_encode([
                ['nombre' => 'Consulta General',      'precio' => 250.00],
                ['nombre' => 'Vacunación',            'precio' => 180.00],
                ['nombre' => 'Desparasitación',       'precio' => 150.00],
                ['nombre' => 'Cirugía',               'precio' => 1500.00],
                ['nombre' => 'Radiografía',           'precio' => 400.00],
                ['nombre' => 'Baño y Estética',       'precio' => 200.00],
            ]),
            'metodos_pago'        => json_encode(['Efectivo', 'Tarjeta de crédito', 'Transferencia bancaria']),
            'mensaje_confirmacion'=> 'Estimado(a) {nombre}, le confirmamos su cita en VetCare para {mascota} el {fecha} a las {hora}. Para cualquier cambio contáctenos al {telefono}.',
            'mensaje_whatsapp'    => 'Hola {nombre} 🐾, su cita en VetCare para {mascota} está confirmada para el {fecha} a las {hora}. ¡Te esperamos!',
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion');
    }
};
