<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unidad_medida', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre', 80);           // "Metro cúbico", "Kilovatio hora"
            $table->string('simbolo', 10)->unique(); // "m³", "kWh", "gal"
            $table->string('icono', 50)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unidad_medida');
    }
};
