<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Catálogo ÚNICO y PLANO (sin columna contexto, por decisión de diseño):
        // permiso de funcionamiento, Defensa Civil, ficha RUC, partida SUNARP,
        // registro de marca INDECOPI, boleta de venta, factura,
        // notificación, constancia de pago, etc.
        Schema::create('tipo_documento_negocio', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre');
            $table->string('icono')->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_documento_negocio');
    }
};
