<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_laborales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empleo_id')->constrained('empleos')->cascadeOnDelete();
            $table->foreignUuid('tipo_documento_laboral_id')->constrained('tipo_documento_laboral');

            $table->string('titulo');
            $table->string('numero_documento')->nullable();
            $table->unsignedSmallInteger('periodo_mes')->nullable(); // 1-12, para boletas
            $table->unsignedSmallInteger('periodo_anio')->nullable(); // para boletas
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
        Schema::dropIfExists('documentos_laborales');
    }
};
