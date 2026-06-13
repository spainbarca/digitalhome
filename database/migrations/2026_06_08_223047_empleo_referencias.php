<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleo_referencias', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empleo_id')->constrained('empleos')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('cargo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('relacion')->nullable(); // jefe directo, colega, RRHH
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleo_referencias');
    }
};
