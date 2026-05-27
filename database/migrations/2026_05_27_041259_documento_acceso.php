<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documento_acceso', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Polimórfico: funciona con documentos_servicio, medicos, etc.
            $table->uuidMorphs('documento');            // documento_id + documento_type

            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('permiso', ['ver', 'editar'])->default('ver');
            $table->timestamps();

            $table->unique(['documento_id', 'documento_type', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documento_acceso');
    }
};
