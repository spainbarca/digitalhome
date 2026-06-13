<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('capacitaciones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('hogar_id')->constrained('hogares')->cascadeOnDelete();
            $table->foreignUuid('hogar_miembro_id')->constrained('hogar_miembros')->cascadeOnDelete();

            // vinculación opcional a un empleo y a una institución educativa existente
            $table->foreignUuid('empleo_id')->nullable()->constrained('empleos')->nullOnDelete();
            $table->foreignUuid('institucion_educativa_id')->nullable()->constrained('instituciones_educativas')->nullOnDelete();
            $table->foreignUuid('tipo_capacitacion_id')->nullable()->constrained('tipo_capacitacion')->nullOnDelete();

            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('institucion_nombre')->nullable(); // si no está en instituciones_educativas
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->date('fecha_vencimiento')->nullable(); // certificaciones que caducan
            $table->string('file_path')->nullable(); // certificado
            $table->text('notas')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('capacitaciones');
    }
};
