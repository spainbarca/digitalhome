<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regimen_tributario', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('codigo');              // NRUS, RER, RMT, RG
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('icono')->nullable();   // Material Icon
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regimen_tributario');
    }
};
