<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pagos_educativos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('hogar_id')->constrained('hogares')->cascadeOnDelete();
            $table->foreignUuid('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->foreignId('moneda_id')->nullable()->constrained('monedas')->restrictOnDelete();
            $table->string('concepto', 200)->nullable()->comment('Ej: Pensión Enero 2026, Matrícula 2026');
            $table->string('mes_correspondiente', 20)->nullable()->comment('Ej: 2026-01');
            $table->decimal('monto', 10, 2);
            $table->date('fecha_vencimiento')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->enum('estado', ['pendiente', 'pagado', 'vencido'])->default('pendiente');
            $table->string('comprobante_path', 500)->nullable()->comment('Voucher o boleta de pago');
            $table->string('comprobante_nombre_original', 255)->nullable();
            $table->string('comprobante_mime', 100)->nullable();
            $table->unsignedBigInteger('comprobante_size')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });       //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_educativos');
    }
};
