<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ubigeo_departamentos', function (Blueprint $table) {
            $table->string('inei', 6)->primary();
            $table->string('reniec', 6)->nullable()->unique();
            $table->string('departamento', 100);
            $table->string('iso_3166_2', 10)->nullable();
            $table->string('fips', 10)->nullable();
            $table->string('capital', 100)->nullable();
            $table->decimal('superficie', 12, 2)->nullable();
            $table->decimal('pob_densidad_2020', 10, 2)->nullable();
            $table->decimal('altitude', 10, 2)->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->decimal('indice_densidad_estado', 8, 4)->nullable();
            $table->decimal('indice_vulnerabilidad_alimentaria', 8, 4)->nullable();
            $table->decimal('idh_2019', 8, 4)->nullable();
            $table->decimal('pct_pobreza_total', 8, 4)->nullable();
            $table->decimal('pct_pobreza_extrema', 8, 4)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubigeo_departamentos');
    }
};
