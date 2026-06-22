<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_viaje', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('viaje_id')->constrained('viajes');

            $table->string('descripcion');                 // "Pasaporte vigente", "Comprar dólares"...
            $table->boolean('completado')->default(false);

            // Opcionales: responsable y fecha límite del pendiente.
            $table->foreignUuid('hogar_miembro_id')->nullable()->constrained('hogar_miembros');
            $table->date('fecha_limite')->nullable();

            $table->unsignedInteger('orden')->default(0);
            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_viaje');
    }
};
