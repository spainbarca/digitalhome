<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            // nombre acortado de la empresa (ej. "BCP" para Banco de Crédito del Perú)
            $table->string('sigla')->nullable()->after('nombre_comercial');
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('sigla');
        });
    }
};
