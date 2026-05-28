<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etiquetables', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('etiqueta_id')->constrained('etiquetas')->cascadeOnDelete();
            $table->uuidMorphs('etiquetable'); // etiquetable_id + etiquetable_type
            $table->timestamps();

            $table->unique(['etiqueta_id', 'etiquetable_id', 'etiquetable_type'], 'etiquetables_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etiquetables');
    }
};
