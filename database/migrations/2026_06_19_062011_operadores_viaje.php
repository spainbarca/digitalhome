<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operadores_viaje', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Patrón ProveedorServicio -> Empresa (8º modelo con empresa_id).
            // empresas usa PK UUID (confirmado por entidades_financieras).
            $table->foreignUuid('empresa_id')->constrained('empresas');

            $table->foreignUuid('tipo_operador_viaje_id')->constrained('tipo_operador_viaje');

            // Override propio (accessor propio -> empresa -> null)
            $table->string('sigla')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('banner_path')->nullable();

            // Específico de operador de viaje
            $table->string('codigo_iata')->nullable()->comment('Código IATA para aerolíneas (ej. LA = LATAM)');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operadores_viaje');
    }
};
