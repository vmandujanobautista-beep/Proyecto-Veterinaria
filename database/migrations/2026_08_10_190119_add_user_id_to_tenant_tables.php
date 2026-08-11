<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Obtener el ID del primer usuario o usar 1 por defecto
        $defaultUserId = DB::table('users')->first()?->id ?? 1;

        $tables = ['clientes', 'mascotas', 'citas'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table_bp) {
                // Agregar la columna como nullable inicialmente
                $table_bp->foreignId('user_id')->nullable()->after('id')->constrained('users')->cascadeOnDelete();
            });

            // Asignar los registros existentes al usuario por defecto
            DB::table($table)->update(['user_id' => $defaultUserId]);

            // Intentar hacer la columna obligatoria (requiere doctrine/dbal en versiones anteriores de Laravel, en L11 nativo)
            Schema::table($table, function (Blueprint $table_bp) {
                $table_bp->unsignedBigInteger('user_id')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['clientes', 'mascotas', 'citas'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table_bp) {
                $table_bp->dropForeign(['user_id']);
                $table_bp->dropColumn('user_id');
            });
        }
    }
};
