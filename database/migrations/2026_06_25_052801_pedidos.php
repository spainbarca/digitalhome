<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Sin tabla de ítems ni catálogo de productos (por diseño):
        // el detalle va en la columna 'descripcion' como texto libre.
        Schema::create('pedidos', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('negocio_id')->constrained('negocios');
            $table->foreignUuid('proveedor_negocio_id')->constrained('proveedores_negocio');

            $table->string('numero')->nullable();
            $table->text('descripcion');                   // texto libre: qué productos se adquieren
            $table->date('fecha');
            $table->foreignId('moneda_id')->nullable()->constrained('monedas');
            $table->decimal('total', 12, 2)->nullable();
            $table->text('observaciones')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
