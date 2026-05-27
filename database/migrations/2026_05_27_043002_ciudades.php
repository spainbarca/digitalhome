<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ciudades', function (Blueprint $table) {
            $table->id();
            $table->string('pais_iso2', 2);
            $table->foreign('pais_iso2')->references('iso2')->on('tbl_paises')->restrictOnDelete();
            $table->string('nombre', 150);
            $table->string('region', 150)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->index('pais_iso2');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ciudades');
    }
};
