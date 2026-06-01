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
        Schema::create('consultas_medicas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('hogar_id')->constrained('hogares')->cascadeOnDelete();
            $table->foreignUuid('hogar_miembro_id')->constrained('hogar_miembros')->cascadeOnDelete();
            $table->foreignUuid('medico_id')->nullable()->constrained('medicos')->nullOnDelete();
            $table->foreignUuid('centro_medico_id')->nullable()->constrained('centros_medicos')->nullOnDelete();
            $table->foreignUuid('especialidad_medica_id')->nullable()->constrained('especialidades_medicas')->nullOnDelete();
            $table->foreignId('moneda_id')->nullable()->constrained('monedas')->restrictOnDelete();
            $table->date('fecha');
            $table->time('hora')->nullable();
            $table->string('motivo', 255)->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('tratamiento_indicado')->nullable();
            $table->decimal('costo', 10, 2)->nullable();
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
        Schema::dropIfExists('consultas_medicas');
    }
};
