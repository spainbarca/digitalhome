<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            // Eliminar FK y columna categoria_id
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');

            // Cambiar tipo_contribuyente de string a FK
            $table->dropColumn('tipo_contribuyente');
            $table->foreignUuid('tipo_contribuyente_id')
                  ->nullable()
                  ->after('sector_id')
                  ->constrained('tipo_contribuyente')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropForeign(['tipo_contribuyente_id']);
            $table->dropColumn('tipo_contribuyente_id');
            $table->string('tipo_contribuyente', 100)->nullable()->after('sector_id');
            $table->foreignUuid('categoria_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('categorias')
                  ->nullOnDelete();
        });
    }
};
