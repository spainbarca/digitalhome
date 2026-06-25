<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Companion: marca una cuenta/producto financiero como del negocio.
        // La exclusión del patrimonio personal es lógica de display (diferida a
        // los widgets/KPIs de Finanzas); aquí solo se habilita la columna.
        Schema::table('productos_financieros', function (Blueprint $table) {
            $table->foreignUuid('negocio_id')->nullable()->constrained('negocios');
        });
    }

    public function down(): void
    {
        Schema::table('productos_financieros', function (Blueprint $table) {
            $table->dropConstrainedForeignId('negocio_id');
        });
    }
};
