<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('hogar_id')->constrained('hogares')->cascadeOnDelete();
            $table->foreignUuid('hogar_miembro_id')->constrained('hogar_miembros')->cascadeOnDelete();
            $table->foreignUuid('empleador_id')->nullable()->constrained('empleadores')->nullOnDelete();
            $table->foreignUuid('modalidad_laboral_id')->nullable()->constrained('modalidad_laboral')->nullOnDelete();
            $table->foreignUuid('estado_empleo_id')->constrained('estado_empleo');

            $table->string('cargo');
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable(); // nullable = trabajo actual
            $table->decimal('salario', 10, 2)->nullable();
            $table->foreignId('moneda_id')->nullable()->constrained('monedas')->nullOnDelete();
            $table->boolean('es_actual')->default(false);
            $table->text('logros')->nullable();
            $table->date('fecha_vencimiento_contrato')->nullable(); // plazo fijo
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleos');
    }
};
