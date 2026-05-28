<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('titulo', 150);
            $table->text('cuerpo')->nullable();

            // Polimórfica: puede apuntar a recordatorios, documentos_servicio, etc.
            $table->nullableUuidMorphs('notificable');

            $table->boolean('leida')->default(false);
            $table->dateTime('leida_en')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'leida']);    // para listar las no leídas rápido
            $table->index(['user_id', 'created_at']); // para el historial ordenado
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
