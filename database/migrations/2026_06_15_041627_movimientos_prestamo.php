<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_prestamo', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('hogar_id')->constrained('hogares');
            $table->foreignUuid('prestatario_id')->constrained('prestatarios');
            $table->foreignUuid('concepto_pago_id')->nullable()->constrained('conceptos_pago');

            // Valores fijos; el código decide el signo del saldo:
            // cargo (presté, sube deuda) | abono (me pagaron, baja) | descuento (condoné, baja)
            $table->string('tipo')->comment('cargo | abono | descuento');

            // Monto real de la línea: se pre-llena con precio_referencial pero es editable.
            $table->decimal('monto', 15, 2);

            $table->string('glosa')->nullable()->comment('Texto libre por línea, ej. "yapeo deuda"');
            $table->date('fecha');

            $table->foreignUuid('metodo_pago_id')->nullable()->constrained('metodo_pago');
            $table->string('comprobante_path')->nullable()->comment('Voucher del movimiento');
            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_prestamo');
    }
};
