<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documentos_educativos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('hogar_id')->constrained('hogares')->cascadeOnDelete();
            $table->foreignUuid('hogar_miembro_id')->constrained('hogar_miembros')->cascadeOnDelete();
            $table->foreignUuid('matricula_id')->nullable()->constrained('matriculas')->nullOnDelete();
            $table->foreignUuid('tipo_documento_educativo_id')->nullable()->constrained('tipo_documento_educativo')->nullOnDelete();
            $table->string('titulo', 200);
            $table->date('fecha_documento')->nullable();
            $table->string('archivo_path', 500)->nullable();
            $table->string('archivo_nombre_original', 255)->nullable();
            $table->string('archivo_mime', 100)->nullable();
            $table->unsignedBigInteger('archivo_size')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_educativos');
    }
};
