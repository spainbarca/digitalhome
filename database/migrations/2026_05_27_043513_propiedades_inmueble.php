<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('propiedades_inmueble', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('persona_id')->constrained('personas')->cascadeOnDelete();
            $table->foreignUuid('tipo_inmueble_id')->constrained('tipo_inmueble')->restrictOnDelete();

            $table->string('alias', 100);               // "Casa principal", "Dpto. Miraflores"
            $table->string('direccion');
            $table->string('referencia', 200)->nullable();

            // --- Ubicación Perú (ubigeo) ---
            $table->string('distrito_inei', 6)->nullable();
            $table->foreign('distrito_inei')->references('inei')->on('ubigeo_distritos')->nullOnDelete();

            // --- Ubicación extranjero (pais + ciudad) ---
            $table->string('pais_iso2', 2)->nullable();
            $table->foreign('pais_iso2')->references('iso2')->on('paises')->nullOnDelete();
            $table->foreignId('ciudad_id')->nullable()->constrained('ciudades')->nullOnDelete();

            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('propiedades_inmueble');
    }
};
