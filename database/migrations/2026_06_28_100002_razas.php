<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('razas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('especie_id')->constrained('especies')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('icono')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('razas');
    }
};
