<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documentos_servicio', function (Blueprint $table) {
            $table->string('documento_url')->nullable()->after('archivo_url');
            $table->string('documento_extension', 10)->nullable()->after('documento_url');
            $table->unsignedBigInteger('documento_tamano_bytes')->nullable()->after('documento_extension');
        });
    }

    public function down(): void
    {
        Schema::table('documentos_servicio', function (Blueprint $table) {
            $table->dropColumn(['documento_url', 'documento_extension', 'documento_tamano_bytes']);
        });
    }
};
