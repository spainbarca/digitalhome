<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestatarios', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('hogar_id')->constrained('hogares');
            // Miembro del hogar que lleva la cuenta. VERIFICAR PK de hogar_miembros (UUID vs entero).
            $table->foreignUuid('miembro_id')->constrained('hogar_miembros');

            $table->foreignId('moneda_id')->constrained('monedas');

            $table->string('nombre');

            // Flag: si la contraparte también es miembro del hogar, se enlaza; si no, solo queda el nombre.
            $table->boolean('es_miembro_hogar')->default(false);
            $table->foreignUuid('hogar_miembro_id')->nullable()->constrained('hogar_miembros');

            $table->string('telefono')->nullable();
            $table->string('foto_path')->nullable();
            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestatarios');
    }
};
