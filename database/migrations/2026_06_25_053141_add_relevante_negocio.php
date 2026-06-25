<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Flag: los documentos legales marcados (contratos, poderes, escritura
        // de constitución, vigencia de poder) asoman en el módulo Negocios.
        Schema::table('tipo_documento_legal', function (Blueprint $table) {
            $table->boolean('relevante_negocio')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('tipo_documento_legal', function (Blueprint $table) {
            $table->dropColumn('relevante_negocio');
        });
    }
};
