<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 9º modelo del patrón ProveedorServicio→Empresa (espeja operadores_viaje).
        // Vive a nivel hogar: un proveedor sirve a cualquier negocio.
        Schema::create('proveedores_negocio', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // VERIFICAR: replicar el tipo/tabla que usa 'viajes' para hogar_id.
            $table->foreignUuid('hogar_id')->constrained('hogares');

            $table->foreignUuid('empresa_id')->nullable()->constrained('empresas');

            $table->string('nombre');
            $table->string('sigla')->nullable();           // sigla_resuelta: propia → empresa → null
            $table->string('condicion_pago')->nullable();  // contado / crédito
            $table->string('contacto')->nullable();        // vendedor / persona de contacto
            $table->string('telefono')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('banner_path')->nullable();

            $table->boolean('activo')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores_negocio');
    }
};
