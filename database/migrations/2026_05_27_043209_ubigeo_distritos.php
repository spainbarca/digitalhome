<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ubigeo_distritos', function (Blueprint $table) {
            $table->string('inei', 6)->primary();
            $table->string('reniec', 6)->nullable()->unique();
            $table->string('departamento_inei', 6);
            $table->foreign('departamento_inei')->references('inei')->on('ubigeo_departamentos')->restrictOnDelete();
            $table->string('provincia_inei', 6);
            $table->foreign('provincia_inei')->references('inei')->on('ubigeo_provincias')->restrictOnDelete();
            $table->string('departamento', 100)->nullable();
            $table->string('provincia', 100)->nullable();
            $table->string('distrito', 100);
            $table->string('region', 100)->nullable();
            $table->string('macroregion_inei', 50)->nullable();
            $table->string('macroregion_minsa', 50)->nullable();
            $table->string('iso_3166_2', 10)->nullable();
            $table->string('fips', 10)->nullable();
            $table->string('capital', 100)->nullable();
            $table->decimal('superficie', 12, 2)->nullable();
            $table->decimal('pob_densidad_2020', 10, 2)->nullable();
            $table->decimal('altitude', 10, 2)->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->decimal('indice_vulnerabilidad_alimentaria', 8, 4)->nullable();
            $table->decimal('idh_2019', 8, 4)->nullable();
            $table->decimal('pct_pobreza_total', 8, 4)->nullable();
            $table->decimal('pct_pobreza_extrema', 8, 4)->nullable();
            $table->timestamps();

            $table->index('departamento_inei');
            $table->index('provincia_inei');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubigeo_distritos');
    }
};
