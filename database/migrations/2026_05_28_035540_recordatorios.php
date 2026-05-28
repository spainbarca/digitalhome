<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recordatorios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('hogar_id')->constrained('hogares')->cascadeOnDelete();

            $table->string('titulo', 150);
            $table->text('descripcion')->nullable();

            $table->date('fecha_vencimiento')->nullable();  // cuando vence el documento/evento
            $table->dateTime('fecha_aviso');                // cuando se dispara la notificación

            // Polimórfica: puede apuntar a documentos_servicio, documentos_medicos, etc.
            $table->nullableUuidMorphs('recordable');

            $table->enum('estado', ['pendiente', 'enviado', 'descartado'])->default('pendiente');
            $table->boolean('repetir')->default(false);     // si se repite anualmente (SOAT, DNI)
            $table->timestamps();
            $table->softDeletes();

            $table->index(['fecha_aviso', 'estado']);       // para el job que los despacha
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recordatorios');
    }
};
