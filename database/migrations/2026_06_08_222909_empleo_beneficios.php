<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleo_beneficios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empleo_id')->constrained('empleos')->cascadeOnDelete();
            $table->foreignUuid('tipo_beneficio_id')->constrained('tipo_beneficio');
            $table->string('detalle')->nullable(); // ej. "Prima AFP", "Cuenta CTS"
            $table->string('entidad')->nullable();  // ej. banco, AFP, EPS
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleo_beneficios');
    }
};
