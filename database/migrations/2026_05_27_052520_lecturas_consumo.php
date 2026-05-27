<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecturas_consumo', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // 1-a-1 con documentos_servicio
            $table->foreignUuid('documento_id')
                  ->unique()
                  ->constrained('documentos_servicio')
                  ->cascadeOnDelete();

            $table->decimal('lectura_anterior', 12, 4)->nullable();
            $table->decimal('lectura_actual', 12, 4)->nullable();
            $table->decimal('consumo', 12, 4)->nullable();

            // Unidad se hereda: documento → cuenta → proveedor → tipo_servicio → unidad_medida
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturas_consumo');
    }
};
