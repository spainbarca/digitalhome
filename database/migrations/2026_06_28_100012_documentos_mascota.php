<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Multi-FK nullable: el documento cuelga de mascota, de una atención o de un
        // evento sanitario según cuál FK esté seteada. Validación "exactamente un
        // parent" va en el controller, no en la BD.
        Schema::create('documentos_mascota', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('mascota_id')->nullable()->constrained('mascotas')->cascadeOnDelete();
            $table->foreignUuid('atencion_veterinaria_id')->nullable()->constrained('atenciones_veterinarias')->cascadeOnDelete();
            $table->foreignUuid('evento_sanitario_id')->nullable()->constrained('eventos_sanitarios')->cascadeOnDelete();
            $table->foreignUuid('tipo_documento_mascota_id')->constrained('tipo_documento_mascota');

            $table->string('nombre');
            $table->string('archivo_path');
            $table->date('fecha')->nullable();
            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_mascota');
    }
};
