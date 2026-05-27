<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_documento', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10)->unique();    // DNI, RUC, CE, PAS
            $table->string('nombre', 100);
            $table->string('descripcion')->nullable();
            $table->unsignedTinyInteger('longitud')->nullable();  // max caracteres
            $table->boolean('es_numerico')->default(false);
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_documento');
    }
};
