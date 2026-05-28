<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_documento_servicio', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre', 100);      // "Recibo", "Contrato", "Carta", "Constancia"
            $table->string('icono', 50)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique('nombre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_documento_servicio');
    }
};
