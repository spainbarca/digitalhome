<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comercios', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('hogar_id')->constrained('hogares');

            // Datos generales (RUC, razón social, logo, contacto) viven en empresas
            $table->foreignUuid('empresa_id')->constrained('empresas');

            // Tipo de establecimiento (catálogo)
            $table->foreignUuid('tipo_comercio_id')->nullable()->constrained('tipo_comercio');

            // Identificación e imágenes propias del comercio (logo se toma de empresa)
            $table->string('nombre_referencial')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('imagen_path')->nullable();
            $table->string('banner_path')->nullable();

            $table->boolean('es_online')->default(false);

            // Ubicación real del local (distinta de la dirección fiscal de la empresa).
            // Mismo patrón que PropiedadInmueble. Relaciones a nivel de modelo:
            //   pais_iso2     -> paises.iso2
            //   distrito_inei -> ubigeo_distritos.inei
            $table->string('direccion')->nullable();
            $table->char('pais_iso2', 2)->nullable();
            $table->foreignId('ciudad_id')->nullable()->constrained('ciudades');
            $table->string('distrito_inei', 6)->nullable();

            $table->text('notas')->nullable();
            $table->boolean('activo')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comercios');
    }
};
