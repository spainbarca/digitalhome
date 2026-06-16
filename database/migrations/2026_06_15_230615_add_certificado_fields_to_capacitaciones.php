<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('capacitaciones', function (Blueprint $table) {
            $table->string('codigo_certificado')->nullable()->after('fecha_vencimiento');
            $table->string('url_verificacion')->nullable()->after('codigo_certificado');
            $table->unsignedSmallInteger('horas_academicas')->nullable()->after('url_verificacion');
        });
    }

    public function down(): void
    {
        Schema::table('capacitaciones', function (Blueprint $table) {
            $table->dropColumn(['codigo_certificado', 'url_verificacion', 'horas_academicas']);
        });
    }
};
