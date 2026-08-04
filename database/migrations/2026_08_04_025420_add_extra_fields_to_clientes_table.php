<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega campos adicionales a la tabla clientes para el modal mejorado:
 * apellido_paterno, apellido_materno, codigo_pais, codigo_postal, foto, estado
 * Se renombra apellido → apellido_paterno manteniendo compatibilidad.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Agregar apellido_paterno como alias del campo apellido existente
            $table->string('apellido_paterno', 100)->nullable()->after('nombre');
            $table->string('apellido_materno', 100)->nullable()->after('apellido_paterno');
            $table->string('codigo_pais', 10)->nullable()->default('+52')->after('telefono');
            $table->string('codigo_postal', 10)->nullable()->after('direccion');
            $table->string('foto', 500)->nullable()->after('codigo_postal');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->after('foto');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn([
                'apellido_paterno',
                'apellido_materno',
                'codigo_pais',
                'codigo_postal',
                'foto',
                'estado',
            ]);
        });
    }
};
