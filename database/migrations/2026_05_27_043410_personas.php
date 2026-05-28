<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Datos básicos
            $table->string('nombres', 100);
            $table->string('apellido_paterno', 80)->nullable();
            $table->string('apellido_materno', 80)->nullable();

            // Identidad
            $table->foreignId('tipo_documento_id')
                  ->nullable()
                  ->constrained('tipos_documento')
                  ->nullOnDelete();
            $table->string('numero_documento', 20)->nullable();

            // Datos personales
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['M', 'F', 'otro'])->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('avatar_url')->nullable();

            // Tipo de sangre (útil para documentos médicos)
            $table->string('tipo_sangre', 5)->nullable(); // O+, A-, etc.

            // Relación con hogar
            $table->foreignUuid('hogar_id')
                  ->constrained('hogares')
                  ->cascadeOnDelete();

            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['hogar_id', 'tipo_documento_id', 'numero_documento'], 'personas_doc_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
