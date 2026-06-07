<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Hogar activo de sesión
            $table->foreignUuid('hogar_id')->constrained('hogares');

            // Miembro que realizó la compra
            $table->foreignUuid('miembro_id')->constrained('hogar_miembros');

            // Dónde se compró (empresa -> comercio -> compra) — opcional
            $table->foreignUuid('comercio_id')->nullable()->constrained('comercios');

            // Catálogos
            $table->foreignUuid('categoria_compra_id')->constrained('categorias_compra');
            $table->foreignUuid('metodo_pago_id')->constrained('metodo_pago');

            // Moneda como entero (PK entero en monedas)
            $table->foreignId('moneda_id')->constrained('monedas');

            // Datos de la compra
            $table->date('fecha_compra');
            $table->decimal('total', 10, 2)->default(0);
            $table->string('concepto');
            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
