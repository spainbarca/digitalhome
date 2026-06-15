<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_financieros', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('hogar_id')->constrained('hogares');

            // FKs flexibles: estado de cuenta/cronograma -> producto; voucher -> transacción
            $table->foreignUuid('producto_financiero_id')->nullable()->constrained('productos_financieros');
            $table->foreignUuid('transaccion_id')->nullable()->constrained('transacciones');

            $table->foreignUuid('tipo_documento_financiero_id')->constrained('tipo_documento_financiero');

            $table->string('titulo');
            $table->string('periodo')->nullable()->comment('Ej. 2026-05 para estados de cuenta');
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->string('archivo_path')->nullable()->comment('Ruta del PDF');
            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_financieros');
    }
};
