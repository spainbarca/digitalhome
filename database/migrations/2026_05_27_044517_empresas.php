<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Relación con categoría (define para qué sirve esta empresa)
            $table->foreignUuid('categoria_id')->constrained('categorias')->restrictOnDelete();

            // --- Datos SUNAT ---
            $table->string('ruc', 11)->unique();
            $table->string('razon_social', 200);
            $table->string('nombre_comercial', 200)->nullable();
            $table->string('tipo_contribuyente', 100)->nullable();  // "SOCIEDAD ANONIMA", etc.
            $table->string('estado_sunat', 50)->nullable();         // "ACTIVO", "BAJA"
            $table->string('condicion_sunat', 50)->nullable();      // "HABIDO", "NO HABIDO"

            // Dirección fiscal (viene de SUNAT, usando ubigeo)
            $table->string('direccion_fiscal')->nullable();
            $table->string('distrito_inei', 6)->nullable();
            $table->foreign('distrito_inei')->references('inei')->on('ubigeo_distritos')->nullOnDelete();

            // Fecha de inicio de actividades
            $table->date('fecha_inicio_actividades')->nullable();

            // --- Datos de contacto (complementados manualmente) ---
            $table->string('telefono', 30)->nullable();
            $table->string('sitio_web')->nullable();
            $table->string('email', 150)->nullable();
            $table->string('logo_url')->nullable();

            // Control de sincronización con SUNAT
            $table->timestamp('sunat_sincronizado_en')->nullable();

            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('categoria_id');
            $table->index('ruc');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
