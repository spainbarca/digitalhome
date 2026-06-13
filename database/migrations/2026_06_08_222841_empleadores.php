<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleadores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empresa_id')->nullable()->constrained('empresas')->nullOnDelete();
            $table->string('nombre'); // nombre propio si no tiene empresa vinculada
            $table->text('descripcion')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('imagen_path')->nullable();
            $table->string('banner_path')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('sitio_web')->nullable();
            $table->string('direccion')->nullable();
            $table->string('distrito_inei', 6)->nullable();
            $table->foreign('distrito_inei')->references('inei')->on('ubigeo_distritos')->nullOnDelete();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleadores');
    }
};
