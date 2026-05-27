<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monedas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('codigo', 3)->unique();     // ISO 4217: PEN, USD, EUR
            $table->string('simbolo', 10);             // S/, $, €
            $table->decimal('tasa_cambio', 12, 6)->default(1.000000);
            $table->boolean('moneda_local')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monedas');
    }
};
