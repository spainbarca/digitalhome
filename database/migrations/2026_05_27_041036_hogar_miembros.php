<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hogar_miembros', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('hogar_id')->constrained('hogares')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('parentesco_id')->nullable()->constrained('parentescos')->nullOnDelete();

            $table->enum('rol', ['admin', 'editor', 'viewer'])->default('viewer');
            $table->string('apodo', 60)->nullable();
            $table->enum('estado', ['activo', 'suspendido'])->default('activo');
            $table->timestamps();

            $table->unique(['hogar_id', 'user_id']);
            $table->index('hogar_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hogar_miembros');
    }
};
