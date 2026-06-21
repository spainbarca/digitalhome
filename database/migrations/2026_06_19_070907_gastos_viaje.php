<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gastos_viaje', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('viaje_id')->constrained('viajes');
            // Quién pagó (opcional).
            $table->foreignUuid('hogar_miembro_id')->nullable()->constrained('hogar_miembros');
            $table->foreignUuid('categoria_gasto_viaje_id')->constrained('categoria_gasto_viaje');

            $table->string('descripcion');            // "Propina mozo", "Taxi al hotel"...
            $table->decimal('monto', 12, 2);
            $table->foreignId('moneda_id')->constrained('monedas'); // PK entero
            $table->date('fecha')->nullable();

            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gastos_viaje');
    }
};
