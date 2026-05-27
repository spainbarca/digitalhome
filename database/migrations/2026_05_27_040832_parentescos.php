<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parentescos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre', 80);           // "Padre", "Madre", "Hijo", "Abuelo"
            $table->string('nombre_inverso', 80)->nullable(); // "Hijo" es inverso de "Padre"
            $table->enum('grupo', [
                'padres',       // Padre, Madre, Padrastro, Madrastra
                'hijos',        // Hijo, Hija, Hijastro
                'hermanos',     // Hermano, Hermana
                'abuelos',      // Abuelo, Abuela
                'nietos',       // Nieto, Nieta
                'tios',         // Tío, Tía
                'primos',       // Primo, Prima
                'conyuges',     // Esposo, Esposa, Conviviente
                'otros',        // Cuñado, Suegro, etc.
            ]);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique('nombre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parentescos');
    }
};
