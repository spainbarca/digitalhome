<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('negocios', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // VERIFICAR: replicar el tipo/tabla que usa 'viajes' para hogar_id.
            $table->foreignUuid('hogar_id')->constrained('hogares');

            // Identidad fiscal (RUC, razón social, dirección, logo) vía empresas.
            $table->foreignUuid('empresa_id')->nullable()->constrained('empresas');

            // Representante legal. VERIFICAR: replicar el target de compras.miembro_id
            // (probablemente 'personas'; ajustar si en compras apunta a otra tabla).
            $table->foreignUuid('miembro_id')->nullable()->constrained('personas');

            $table->foreignUuid('regimen_tributario_id')->nullable()->constrained('regimen_tributario');
            $table->foreignUuid('tipo_sociedad_id')->nullable()->constrained('tipo_sociedad');
            // Giro/sector: se DERIVA vía la empresa (empresas.sector_id ya existe),
            // no se duplica aquí. Acceso: $negocio->empresa->sector.
            // Alternativa (sector propio del negocio, independiente del de la empresa):
            // $table->foreignUuid('sector_id')->nullable()->constrained('sectores');
            $table->foreignUuid('estado_negocio_id')->nullable()->constrained('estado_negocio');

            $table->string('nombre')->nullable();              // display
            $table->string('ciiu')->nullable();                // código CIIU ficha RUC
            $table->date('fecha_constitucion')->nullable();
            $table->date('fecha_inicio_actividades')->nullable(); // SUNAT
            $table->string('partida_registral')->nullable();   // SUNARP
            $table->string('oficina_registral')->nullable();
            $table->text('observaciones')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negocios');
    }
};
