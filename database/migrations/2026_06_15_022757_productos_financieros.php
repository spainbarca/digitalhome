<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos_financieros', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('entidad_financiera_id')->constrained('entidades_financieras');
            $table->foreignUuid('tipo_producto_financiero_id')->constrained('tipo_producto_financiero');
            $table->foreignUuid('estado_producto_id')->constrained('estado_producto');

            // Titular principal. VERIFICAR tipo PK de hogar_miembros (UUID vs entero).
            $table->foreignUuid('miembro_id')->constrained('hogar_miembros')->comment('Titular principal');

            // Self-FK: tarjeta de débito/adicional que cuelga de otra cuenta o tarjeta
            $table->foreignUuid('producto_padre_id')->nullable()->constrained('productos_financieros');

            $table->foreignId('moneda_id')->constrained('monedas');

            $table->string('alias')->comment('Etiqueta para el usuario, ej. "Cuenta sueldo BCP"');

            // Identificadores
            $table->string('numero_cuenta')->nullable();
            $table->string('cci')->nullable();
            $table->string('ultimos_digitos', 4)->nullable()->comment('Últimos 4 dígitos de tarjeta');

            // Saldos y condiciones (todas nullable; aplican según tipo)
            $table->decimal('saldo_actual', 15, 2)->nullable()->comment('Saldo / fondo AFP / saldo CTS');
            $table->decimal('linea_credito', 15, 2)->nullable();
            $table->decimal('tasa_tea', 6, 3)->nullable()->comment('Tasa Efectiva Anual %');
            $table->decimal('tasa_tcea', 6, 3)->nullable()->comment('Tasa de Costo Efectivo Anual %');
            $table->decimal('cuota', 15, 2)->nullable();
            $table->unsignedSmallInteger('plazo_meses')->nullable();

            // Tarjetas: día del mes (recurrente). Plazo fijo/tarjeta: fecha_vencimiento.
            $table->unsignedTinyInteger('dia_corte')->nullable()->comment('Día del mes (tarjetas)');
            $table->unsignedTinyInteger('dia_pago')->nullable()->comment('Día del mes (tarjetas)');
            $table->date('fecha_apertura')->nullable();
            $table->date('fecha_vencimiento')->nullable()->comment('Plazo fijo / vencimiento de tarjeta');

            // Cuenta compartida (titularidad real diferida a producto_titulares)
            $table->boolean('es_mancomunada')->default(false);

            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos_financieros');
    }
};
