<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('viajes', function (Blueprint $table) {
            // Nuevo FK al catálogo. Nullable: los viajes ya existentes quedan sin
            // estado hasta reasignarlo desde el form (no se mapea el string viejo).
            $table->foreignUuid('estado_viaje_id')->nullable()->after('fecha_fin')
                  ->constrained('estado_viaje');
        });

        // Elimina la columna string anterior.
        Schema::table('viajes', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }

    public function down(): void
    {
        Schema::table('viajes', function (Blueprint $table) {
            $table->string('estado')->default('planificado')
                  ->comment('planificado | en_curso | finalizado | cancelado');
        });

        Schema::table('viajes', function (Blueprint $table) {
            $table->dropForeign(['estado_viaje_id']);
            $table->dropColumn('estado_viaje_id');
        });
    }
};
