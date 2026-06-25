<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Patrón multi-FK nullable (espeja documentos_viaje): un mismo documento
        // cuelga de un negocio (permiso), un pedido (boleta de venta) o un pago
        // (notificación / constancia), según cuál FK esté seteada.
        Schema::create('documentos_negocio', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('negocio_id')->nullable()->constrained('negocios');
            $table->foreignUuid('pedido_id')->nullable()->constrained('pedidos');
            $table->foreignUuid('pago_negocio_id')->nullable()->constrained('pagos_negocio');
            $table->foreignUuid('tipo_documento_negocio_id')->nullable()->constrained('tipo_documento_negocio');

            $table->string('nombre');
            $table->string('archivo_path')->nullable();
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->text('observaciones')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_negocio');
    }
};
