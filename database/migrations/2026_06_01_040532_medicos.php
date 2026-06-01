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
        Schema::create('medicos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('hogar_id')->constrained('hogares')->cascadeOnDelete();
            $table->foreignUuid('especialidad_medica_id')->nullable()->constrained('especialidades_medicas')->nullOnDelete();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('cmp', 20)->nullable()->comment('Código del Colegio Médico del Perú');
            $table->string('telefono', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('imagen_path', 500)->nullable()->comment('Foto del médico');
            $table->text('notas')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicos');
    }
};
