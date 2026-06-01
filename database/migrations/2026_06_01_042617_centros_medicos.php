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
        Schema::create('centros_medicos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('hogar_id')->constrained('hogares')->cascadeOnDelete();
            $table->foreignUuid('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignUuid('tipo_centro_medico_id')->nullable()->constrained('tipos_centro_medico')->nullOnDelete();
            $table->string('nombre_referencial', 150)->nullable()->comment('Alias del centro para el hogar, ej: Clínica de mamá');
            $table->string('imagen_path', 500)->nullable();
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
        Schema::dropIfExists('centros_medicos');
    }
};
