<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cita_confirmaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas')->cascadeOnDelete();
            $table->enum('canal', ['whatsapp', 'email']);
            $table->string('destinatario');
            $table->enum('estado', ['pendiente', 'enviado', 'entregado', 'error'])->default('pendiente');
            $table->text('mensaje_error')->nullable();
            $table->string('provider_message_id')->nullable();
            $table->timestamp('fecha_envio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cita_confirmaciones');
    }
};
