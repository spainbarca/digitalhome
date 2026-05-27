<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores_servicio', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Empresa base (datos SUNAT)
            // nullable: permite proveedores sin RUC (ej: cisterna, informal)
            $table->foreignUuid('empresa_id')->nullable()->constrained('empresas')->nullOnDelete();

            // Tipo de servicio que brinda este proveedor
            $table->foreignUuid('tipo_servicio_id')->constrained('tipo_servicio')->restrictOnDelete();

            // Datos propios del proveedor (complementan o reemplazan si no hay empresa)
            $table->string('nombre_comercial', 150)->nullable(); // si difiere de razon_social
            $table->string('telefono', 30)->nullable();
            $table->string('sitio_web')->nullable();
            $table->string('logo_url')->nullable();

            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('empresa_id');
            $table->index('tipo_servicio_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores_servicio');
    }
};
