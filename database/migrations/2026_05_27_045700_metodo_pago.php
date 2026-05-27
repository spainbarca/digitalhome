<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metodo_pago', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre', 80);       // "Yape", "BCP", "Ventanilla", "Débito automático"
            $table->string('icono', 50)->nullable();
            $table->string('logo', 255)->nullable(); // URL o path del logo para la UI
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique('nombre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metodo_pago');
    }
};
