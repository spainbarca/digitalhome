<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('viaje_destinos', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('viaje_id')->constrained('viajes');

            // Nombre/etiqueta del destino para la línea de tiempo (siempre presente).
            $table->string('nombre');                 // "Cusco", "Arequipa"...

            // Ubicación (mismo patrón que create/edit de propiedades):
            //   Perú       -> distrito_inei
            //   Extranjero -> pais_iso2 + ciudad_id
            // Solo se llena uno de los dos lados.
            $table->string('distrito_inei')->nullable()->comment('Perú: referencia a ubigeo_distritos (código INEI)');
            $table->string('pais_iso2', 2)->nullable()->comment('Extranjero: referencia a paises.iso2');
            $table->foreignId('ciudad_id')->nullable()->constrained('ciudades'); // Extranjero (PK entero)

            $table->date('fecha_llegada')->nullable();
            $table->date('fecha_salida')->nullable();
            $table->unsignedInteger('orden')->default(0)->comment('Orden en la línea de tiempo');

            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viaje_destinos');
    }
};
