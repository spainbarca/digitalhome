<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Calendario sanitario unificado — Opción B: tabla única con columnas
        // type-specific nullable, discriminadas por tipo_evento_sanitario_id.
        Schema::create('eventos_sanitarios', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('mascota_id')->constrained('mascotas')->cascadeOnDelete();
            $table->foreignUuid('tipo_evento_sanitario_id')->constrained('tipo_evento_sanitario');
            $table->foreignUuid('centro_veterinario_id')->nullable()->constrained('centros_veterinarios');

            $table->date('fecha_aplicacion');
            $table->string('producto')->nullable();        // nombre vacuna / antiparasitario
            $table->string('lote')->nullable();            // type-specific: vacuna
            $table->date('proxima_dosis')->nullable();     // alimenta recordatorios transversal
            $table->decimal('costo', 12, 2)->nullable();
            $table->foreignId('moneda_id')->nullable()->constrained('monedas');
            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos_sanitarios');
    }
};
