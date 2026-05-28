<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfiles_miembro', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            $table->date('fecha_nacimiento')->nullable();
            $table->enum('genero', ['masculino', 'femenino', 'otro', 'prefiero_no_decir'])->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('avatar_url')->nullable();

            $table->string('numero_dni', 20)->nullable();
            $table->string('tipo_sangre', 5)->nullable();
            $table->text('alergias')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfiles_miembro');
    }
};
