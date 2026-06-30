<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atenciones_veterinarias', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('mascota_id')->constrained('mascotas')->cascadeOnDelete();
            $table->foreignUuid('centro_veterinario_id')->nullable()->constrained('centros_veterinarios');

            $table->date('fecha');
            $table->string('motivo');
            $table->text('diagnostico')->nullable();
            $table->text('tratamiento')->nullable();
            $table->decimal('costo', 12, 2)->nullable();
            $table->foreignId('moneda_id')->nullable()->constrained('monedas');
            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atenciones_veterinarias');
    }
};
