<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_servicio', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('cuenta_id')->constrained('cuentas_servicio')->cascadeOnDelete();
            $table->foreignId('subido_por')->constrained('users')->restrictOnDelete();

            // Tablas auxiliares
            $table->foreignUuid('tipo_documento_id')->constrained('tipo_documento_servicio')->restrictOnDelete();
            $table->foreignUuid('estado_pago_id')->constrained('estado_pago')->restrictOnDelete();
            $table->foreignUuid('metodo_pago_id')->nullable()->constrained('metodo_pago')->nullOnDelete();
            $table->foreignId('moneda_id')->nullable()->constrained('tbl_monedas')->restrictOnDelete();
            $table->foreignUuid('hogar_id')->nullable()->constrained('hogares')->nullOnDelete();
            $table->foreignUuid('visibilidad_id')->constrained('tipo_visibilidad')->restrictOnDelete();

            // Período facturado
            $table->date('periodo_inicio')->nullable();
            $table->date('periodo_fin')->nullable();

            // Montos
            $table->decimal('monto_total', 10, 2)->nullable();

            // Fechas
            $table->date('fecha_vencimiento')->nullable();
            $table->date('fecha_pago')->nullable();

            // Archivo — referencia por extensión, no por id
            $table->string('archivo_url')->nullable();
            $table->string('extension', 10)->nullable();
            $table->foreign('extension')->references('extension')->on('tipo_archivo')->nullOnDelete();
            $table->unsignedBigInteger('tamano_bytes')->nullable();

            $table->text('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['cuenta_id', 'periodo_inicio']);
            $table->index(['estado_pago_id', 'fecha_vencimiento']);
            $table->index(['hogar_id', 'visibilidad_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_servicio');
    }
};
