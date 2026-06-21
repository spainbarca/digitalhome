<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            // Companion: marca una compra como gasto de un viaje (con boleta).
            // Mismo patrón que producto_financiero_id en Finanzas.
            $table->foreignUuid('viaje_id')->nullable()->constrained('viajes');
        });
    }

    public function down(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->dropForeign(['viaje_id']);
            $table->dropColumn('viaje_id');
        });
    }
};
