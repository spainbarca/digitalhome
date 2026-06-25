<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pagos institucionales (SUNAT, municipalidad, etc.). Registra pagos HECHOS,
        // no es motor de obligaciones recurrentes (descartado por diseño).
        // Los documentos (notificación, constancia de pago) cuelgan en documentos_negocio.
        Schema::create('pagos_negocio', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('negocio_id')->constrained('negocios');

            // Entidad receptora (SUNAT, municipalidad) vive como empresa con RUC.
            $table->foreignUuid('entidad_receptora_id')->nullable()->constrained('empresas');

            // Concepto opcional.
            $table->foreignUuid('tipo_pago_negocio_id')->nullable()->constrained('tipo_pago_negocio');

            $table->decimal('monto', 12, 2);
            $table->foreignId('moneda_id')->nullable()->constrained('monedas');
            $table->date('fecha_pago');
            $table->string('periodo')->nullable();         // ej. 2026-05
            $table->text('observaciones')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos_negocio');
    }
};
