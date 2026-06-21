<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('viaje_id')->constrained('viajes');

            // Operador (aerolínea/hotel/bus). Nullable: no siempre se registra.
            $table->foreignUuid('operador_viaje_id')->nullable()->constrained('operadores_viaje');

            $table->foreignUuid('tipo_reserva_id')->constrained('tipo_reserva');
            // Medio de transporte (solo aplica a reservas de transporte).
            $table->foreignUuid('tipo_transporte_id')->nullable()->constrained('tipo_transporte');
            $table->foreignUuid('estado_reserva_id')->nullable()->constrained('estado_reserva');

            // --- Bloque compartido (Opción B) ---
            $table->string('titulo')->nullable()->comment('Descripción corta de la reserva');
            $table->string('codigo_reserva')->nullable()->comment('Localizador / PNR');
            $table->dateTime('fecha_inicio')->nullable()->comment('Salida / check-in');
            $table->dateTime('fecha_fin')->nullable()->comment('Llegada / check-out');
            $table->decimal('monto', 12, 2)->nullable();
            $table->foreignId('moneda_id')->nullable()->constrained('monedas'); // PK entero

            // --- Sección transporte (vuelo/bus/tren) ---
            $table->string('origen')->nullable();
            $table->string('destino')->nullable();
            $table->string('numero_servicio')->nullable()->comment('Nro de vuelo / bus / tren');
            $table->string('asiento')->nullable();

            // --- Sección hospedaje ---
            $table->unsignedInteger('num_noches')->nullable();
            $table->string('tipo_habitacion')->nullable();
            $table->string('direccion')->nullable();

            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
