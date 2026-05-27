<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuentas_servicio', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // La propiedad a la que pertenece el servicio
            $table->foreignUuid('propiedad_id')->constrained('propiedades_inmueble')->cascadeOnDelete();

            // El proveedor (Luz del Sur, Sedapal, etc.)
            $table->foreignUuid('proveedor_id')->constrained('proveedores_servicio')->restrictOnDelete();

            // El familiar que figura como titular en el contrato/recibo
            // Puede ser el dueño de la propiedad u otro miembro del hogar
            $table->foreignId('titular_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('numero_cuenta', 100);       // Número de suministro / contrato
            $table->enum('estado', ['activa', 'suspendida', 'cancelada'])->default('activa');
            $table->date('fecha_inicio')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['propiedad_id', 'proveedor_id', 'numero_cuenta']);
            $table->index('titular_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuentas_servicio');
    }
};
