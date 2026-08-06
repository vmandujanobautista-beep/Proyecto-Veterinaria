<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Añade índices en las columnas más usadas en búsquedas y filtros de
 * las tablas clientes y mascotas para evitar full-table-scans.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── clientes: nombre y email se usan frecuentemente en WHERE LIKE y ORDER BY ──
        Schema::table('clientes', function (Blueprint $table) {
            // Índice compuesto para búsqueda por nombre + apellido_paterno
            $table->index(['nombre', 'apellido_paterno'], 'clientes_nombre_apellido_index');
            $table->index('email', 'clientes_email_index');
            $table->index('created_at', 'clientes_created_at_index');
        });

        // ── mascotas: nombre, especie, cliente_id (FK ya tiene índice implícito) ──
        Schema::table('mascotas', function (Blueprint $table) {
            $table->index('nombre', 'mascotas_nombre_index');
            $table->index('especie', 'mascotas_especie_index');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropIndex('clientes_nombre_apellido_index');
            $table->dropIndex('clientes_email_index');
            $table->dropIndex('clientes_created_at_index');
        });

        Schema::table('mascotas', function (Blueprint $table) {
            $table->dropIndex('mascotas_nombre_index');
            $table->dropIndex('mascotas_especie_index');
        });
    }
};
