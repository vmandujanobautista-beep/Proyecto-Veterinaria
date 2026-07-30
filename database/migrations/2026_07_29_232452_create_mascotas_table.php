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
        Schema::create('mascotas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre') ;
            $table->string('especie');
            $table->string('raza')->nullable();
            $table->enum('sexo', ['macho', 'hembra'])->nullable();
            $table->decimal('peso', 5, 2)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->text('nota_medica')->nullable();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};
