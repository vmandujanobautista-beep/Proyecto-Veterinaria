<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega campos de perfil extendido a la tabla users.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('fecha_nacimiento')->nullable()->after('email');
            $table->string('telefono', 20)->nullable()->after('fecha_nacimiento');
            $table->string('direccion', 255)->nullable()->after('telefono');
            $table->boolean('fecha_nacimiento_bloqueada')->default(false)->after('direccion');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['fecha_nacimiento', 'telefono', 'direccion', 'fecha_nacimiento_bloqueada']);
        });
    }
};
