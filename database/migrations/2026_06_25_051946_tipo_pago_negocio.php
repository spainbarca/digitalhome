<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Conceptos de pago institucional: impuesto, arbitrios, licencia, multa, tasa, contribución.
        Schema::create('tipo_pago_negocio', function (Blueprint $table) {
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
        Schema::dropIfExists('tipo_pago_negocio');
    }
};
