<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega índices de rendimiento en columnas frecuentemente usadas en
 * cláusulas WHERE y ORDER BY para evitar full-table-scans.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── citas: fecha (WHERE/ORDER BY), estado (WHERE filtro) ──────────
        Schema::table('citas', function (Blueprint $table) {
            $table->index('fecha',  'citas_fecha_index');
            $table->index('estado', 'citas_estado_index');
        });

        // ── ventas: created_at (WHERE/ORDER BY por fecha de venta) ────────
        Schema::table('ventas', function (Blueprint $table) {
            $table->index('created_at', 'ventas_created_at_index');
        });

        // ── productos: categoria (WHERE filtro de categoría) ──────────────
        Schema::table('productos', function (Blueprint $table) {
            $table->index('categoria', 'productos_categoria_index');
        });
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropIndex('citas_fecha_index');
            $table->dropIndex('citas_estado_index');
        });

        Schema::table('ventas', function (Blueprint $table) {
            $table->dropIndex('ventas_created_at_index');
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->dropIndex('productos_categoria_index');
        });
    }
};
