<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entidades_legales', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('empresa_id')->nullable()->constrained('empresas')->nullOnDelete();
            $table->foreignUuid('tipo_entidad_legal_id')->constrained('tipo_entidad_legal');

            $table->string('nombre');
            $table->text('descripcion')->nullable();

            $table->string('logo_path')->nullable();
            $table->string('imagen_path')->nullable();
            $table->string('banner_path')->nullable();

            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('sitio_web')->nullable();
            $table->string('direccion')->nullable();

            // Referencia suave a ubigeo_distritos.inei (mismo patrón que comercios)
            $table->string('distrito_inei', 6)->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entidades_legales');
    }
};
