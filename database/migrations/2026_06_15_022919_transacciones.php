<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('producto_financiero_id')->constrained('productos_financieros')
                ->comment('Producto origen / al que pertenece el movimiento');

            // Transferencia entre cuentas propias: una sola fila origen -> destino
            $table->foreignUuid('producto_destino_id')->nullable()->constrained('productos_financieros')
                ->comment('Solo en transferencias entre cuentas propias');

            $table->foreignUuid('tipo_transaccion_id')->constrained('tipo_transaccion');

            // metodo_pago usa UUID (confirmado)
            $table->foreignUuid('metodo_pago_id')->nullable()->constrained('metodo_pago');

            $table->foreignId('moneda_id')->constrained('monedas');

            $table->decimal('monto', 15, 2);
            $table->dateTime('fecha');
            $table->string('glosa')->nullable()->comment('Descripción del movimiento');
            $table->string('nro_operacion')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transacciones');
    }
};
