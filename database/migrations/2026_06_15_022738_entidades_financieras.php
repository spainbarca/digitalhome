<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entidades_financieras', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Patrón ProveedorServicio -> Empresa (7º modelo con empresa_id).
            // VERIFICAR: si 'empresas' usa PK entero, cambiar a foreignId().
            $table->foreignUuid('empresa_id')->constrained('empresas');

            $table->foreignUuid('tipo_entidad_financiera_id')->constrained('tipo_entidad_financiera');

            // Override propio (accessor propio -> empresa -> null)
            $table->string('sigla')->nullable();
            $table->string('logo_url')->nullable();

            // Específico financiero
            $table->string('codigo_sbs')->nullable()->comment('Código de la entidad ante la SBS');
            $table->string('swift')->nullable()->comment('SWIFT/BIC para transferencias internacionales');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entidades_financieras');
    }
};
