<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_viaje', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Convención documentos_*: hogar_id no-nulo.
            // VERIFICAR nombre de tabla del hogar (hogares / hogar).
            $table->foreignUuid('hogar_id')->constrained('hogares');

            // FKs nullable a ambos (mismo patrón que documentos_financieros).
            $table->foreignUuid('viaje_id')->nullable()->constrained('viajes');
            $table->foreignUuid('reserva_id')->nullable()->constrained('reservas');

            $table->foreignUuid('tipo_documento_viaje_id')->constrained('tipo_documento_viaje');

            $table->string('nombre');
            $table->string('archivo_path');           // convención: archivo_path, no archivo_url

            $table->date('fecha_emision')->nullable();
            $table->date('fecha_vencimiento')->nullable();

            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_viaje');
    }
};
