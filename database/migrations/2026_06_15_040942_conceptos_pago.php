<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conceptos_pago', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('hogar_id')->constrained('hogares');

            $table->string('nombre');
            $table->text('descripcion')->nullable();

            // Precio referencial: pre-llena el monto del movimiento, pero es editable por línea.
            $table->decimal('precio_referencial', 15, 2)->nullable();

            $table->string('imagen_path')->nullable()->comment('Imagen referencial del concepto/producto');

            $table->boolean('activo')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conceptos_pago');
    }
};
