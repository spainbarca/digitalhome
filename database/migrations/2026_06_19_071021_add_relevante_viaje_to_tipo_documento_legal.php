<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tipo_documento_legal', function (Blueprint $table) {
            // Marca tipos legales relevantes para viajes (pasaporte, visa,
            // permiso de menor) para asomar su vencimiento dentro de Viajes.
            $table->boolean('relevante_viaje')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('tipo_documento_legal', function (Blueprint $table) {
            $table->dropColumn('relevante_viaje');
        });
    }
};
