<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mascotas', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('hogar_id')->constrained('hogares');
            $table->foreignUuid('especie_id')->constrained('especies');
            $table->foreignUuid('raza_id')->nullable()->constrained('razas');
            $table->foreignUuid('sexo_mascota_id')->nullable()->constrained('sexo_mascota');
            $table->foreignUuid('origen_mascota_id')->nullable()->constrained('origen_mascota');
            $table->foreignUuid('estado_mascota_id')->constrained('estado_mascota');
            $table->foreignUuid('centro_veterinario_id')->nullable()->constrained('centros_veterinarios');

            $table->string('nombre');
            $table->date('fecha_nacimiento')->nullable();
            $table->boolean('fecha_nacimiento_aproximada')->default(false);
            $table->string('color')->nullable();
            $table->text('senas')->nullable();
            $table->string('microchip')->nullable();
            $table->boolean('esterilizado')->default(false);
            $table->date('fecha_esterilizacion')->nullable();
            $table->string('foto_path')->nullable();
            $table->date('fecha_fallecimiento')->nullable();
            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};
