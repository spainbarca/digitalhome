<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etiquetas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('hogar_id')->constrained('hogares')->cascadeOnDelete();
            $table->string('nombre', 60);
            $table->string('color', 7)->default('#6B7280'); // hex color, ej: #EF4444
            $table->timestamps();

            $table->unique(['hogar_id', 'nombre']); // sin duplicados por hogar
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etiquetas');
    }
};
