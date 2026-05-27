<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_archivo', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre', 50);                   // "PDF", "Imagen JPEG", "Word"
            $table->string('extension', 10)->unique();      // "pdf", "jpg", "docx" → clave natural
            $table->string('mime_type', 100);               // "application/pdf", "image/jpeg"
            $table->string('icono', 50)->nullable();
            $table->boolean('permitido')->default(true);    // controla si se puede subir o no
            $table->unsignedBigInteger('tamano_max_bytes')->nullable(); // límite por tipo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_archivo');
    }
};
