<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_legales', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('hogar_id')->constrained('hogares')->cascadeOnDelete();
            $table->foreignUuid('hogar_miembro_id')->nullable()->constrained('hogar_miembros')->nullOnDelete();
            $table->foreignUuid('propiedad_inmueble_id')->nullable()->constrained('propiedades_inmueble')->nullOnDelete();

            $table->foreignUuid('tipo_documento_legal_id')->constrained('tipo_documento_legal');
            $table->foreignUuid('estado_documento_legal_id')->constrained('estado_documento_legal');
            $table->foreignUuid('entidad_legal_id')->nullable()->constrained('entidades_legales')->nullOnDelete();

            $table->string('titulo');
            $table->string('numero_documento')->nullable();
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_vencimiento')->nullable();

            $table->string('file_path')->nullable();
            $table->text('notas')->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_legales');
    }
};
