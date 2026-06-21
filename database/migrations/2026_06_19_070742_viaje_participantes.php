<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('viaje_participantes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('viaje_id')->constrained('viajes');
            $table->foreignUuid('hogar_miembro_id')->constrained('hogar_miembros');

            $table->string('rol')->nullable()->comment('Ej. organizador, acompañante');

            $table->timestamps();
            $table->softDeletes();

            // Unicidad: se controla en el controller (SoftDeletes hace que un
            // índice único en BD bloquee re-agregar tras eliminar).
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viaje_participantes');
    }
};
