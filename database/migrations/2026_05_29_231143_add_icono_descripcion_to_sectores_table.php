<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sectores', function (Blueprint $table) {
            $table->string('icono', 100)->nullable()->after('nombre');
            $table->string('descripcion', 300)->nullable()->after('icono');
        });
    }

    public function down(): void
    {
        Schema::table('sectores', function (Blueprint $table) {
            $table->dropColumn(['icono', 'descripcion']);
        });
    }
};
