<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paises', function (Blueprint $table) {
            $table->string('id_pais', 10)->primary();
            $table->string('iso2', 2)->unique();
            $table->string('nombre', 100);
            $table->string('nombre_oficial', 150)->nullable();
            $table->string('iso3', 3)->nullable()->unique();
            $table->string('codigo_num', 3)->nullable();
            $table->boolean('miembro_onu')->default(false);
            $table->string('codigo_tel', 20)->nullable();
            $table->string('dominio', 10)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paises');
    }
};
