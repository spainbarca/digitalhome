<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_sociedad', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('sigla')->nullable();   // EIRL, SAC, SRL, SA, SAA, SCRL
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
        Schema::dropIfExists('tipo_sociedad');
    }
};
