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
        Schema::create('matriculas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('hogar_id')->constrained('hogares')->cascadeOnDelete();
            $table->foreignUuid('hogar_miembro_id')->constrained('hogar_miembros')->cascadeOnDelete();
            $table->foreignUuid('institucion_educativa_id')->nullable()->constrained('instituciones_educativas')->nullOnDelete();
            $table->foreignUuid('nivel_educativo_id')->nullable()->constrained('niveles_educativos')->nullOnDelete();
            $table->foreignUuid('turno_educativo_id')->nullable()->constrained('turnos_educativos')->nullOnDelete();
            $table->foreignUuid('estado_matricula_id')->nullable()->constrained('estados_matricula')->nullOnDelete();
            $table->foreignId('moneda_id')->nullable()->constrained('monedas')->restrictOnDelete();
            $table->string('año_lectivo', 20)->nullable()->comment('Ej: 2026, 2026-I');
            $table->string('grado_ciclo', 100)->nullable()->comment('Ej: 3° Primaria, Ciclo V');
            $table->string('nombre_tutor', 150)->nullable();
            $table->string('telefono_tutor', 20)->nullable();
            $table->decimal('costo_mensual', 10, 2)->nullable();
            $table->unsignedTinyInteger('dia_vencimiento_pago')->nullable()->comment('Día del mes, ej: 10');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
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
        Schema::dropIfExists('matriculas');
    }
};
