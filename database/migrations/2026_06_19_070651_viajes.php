<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('viajes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // VERIFICAR nombre de tabla del hogar (hogares / hogar).
            $table->foreignUuid('hogar_id')->constrained('hogares');

            $table->foreignUuid('tipo_viaje_id')->constrained('tipo_viaje');

            $table->string('nombre');                 // "Viaje al sur del Perú, verano 2026"
            $table->text('descripcion')->nullable();

            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();

            // Estado del viaje. OJO: hoy es string simple; si lo quieres como
            // catálogo con color (al estilo estado_reserva), se convierte luego.
            $table->string('estado')->default('planificado')
                  ->comment('planificado | en_curso | finalizado | cancelado');

            $table->decimal('presupuesto', 12, 2)->nullable();
            $table->foreignId('moneda_id')->nullable()->constrained('monedas'); // PK entero

            $table->string('portada_path')->nullable()->comment('Imagen de portada del viaje (tarjeta)');
            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viajes');
    }
};
