<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega campos adicionales a la tabla mascotas:
 * foto y color_pelaje (para el sub-modal de nueva mascota).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mascotas', function (Blueprint $table) {
            $table->string('foto', 500)->nullable()->after('nota_medica');
            $table->string('color_pelaje', 100)->nullable()->after('foto');
        });
    }

    public function down(): void
    {
        Schema::table('mascotas', function (Blueprint $table) {
            $table->dropColumn(['foto', 'color_pelaje']);
        });
    }
};
