<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estado_pago', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre', 80);       // "Pendiente", "Pagado", "Vencido", "En disputa"
            $table->string('color', 7)->nullable();  // para badges en UI
            $table->string('icono', 50)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique('nombre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estado_pago');
    }
};
