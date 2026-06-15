<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conceptos_pago', function (Blueprint $table) {
            // Categoría opcional: en préstamos informales muchos conceptos van sin clasificar.
            $table->foreignUuid('categoria_concepto_id')->nullable()->after('hogar_id')
                ->constrained('categorias_concepto');
        });
    }

    public function down(): void
    {
        Schema::table('conceptos_pago', function (Blueprint $table) {
            $table->dropForeign(['categoria_concepto_id']);
            $table->dropColumn('categoria_concepto_id');
        });
    }
};
