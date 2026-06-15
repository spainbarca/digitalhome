<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beneficiarios', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('producto_financiero_id')->constrained('productos_financieros');

            // Si el beneficiario pertenece al hogar se enlaza; si no, queda solo el nombre.
            // VERIFICAR tipo PK de hogar_miembros.
            $table->foreignUuid('hogar_miembro_id')->nullable()->constrained('hogar_miembros');

            $table->string('nombre')->comment('Texto libre si el beneficiario no está en el hogar');
            $table->string('parentesco')->nullable();
            $table->string('documento_identidad')->nullable()->comment('DNI / CE');
            $table->decimal('porcentaje', 5, 2)->default(100)->comment('Debe sumar 100% por producto');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiarios');
    }
};
