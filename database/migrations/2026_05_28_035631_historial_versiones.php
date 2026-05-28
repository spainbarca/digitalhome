<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_versiones', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Polimórfica: puede versionar documentos_servicio, documentos_medicos, etc.
            $table->uuidMorphs('versionable');

            $table->string('archivo_url');
            $table->string('extension', 10)
                  ->nullable()
                  ->references('extension')->on('tipo_archivo')->nullOnDelete();
            $table->unsignedBigInteger('tamano_bytes')->nullable();

            $table->foreignUuid('subido_por')->constrained('users')->restrictOnDelete();
            $table->text('motivo_cambio')->nullable(); // "Recibo corregido", "Versión actualizada"
            $table->unsignedSmallInteger('numero_version')->default(1);

            $table->timestamps(); // created_at = cuándo se archivó esta versión

            $table->index(['versionable_type', 'versionable_id', 'numero_version'], 'historial_lookup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_versiones');
    }
};
