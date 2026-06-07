<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_compra', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('hogar_id')->constrained('hogares');
            $table->foreignUuid('compra_id')->constrained('compras');
            $table->foreignUuid('tipo_documento_compra_id')->constrained('tipo_documento_compra');

            $table->string('archivo_path');
            $table->string('titulo');
            $table->date('fecha_documento')->nullable();

            // Clave para garantías: alimenta los Recordatorios
            $table->date('fecha_vencimiento')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_compra');
    }
};
