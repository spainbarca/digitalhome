<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->foreignUuid('mascota_id')->nullable()->after('producto_financiero_id')
                ->constrained('mascotas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->dropForeign(['mascota_id']);
            $table->dropColumn('mascota_id');
        });
    }
};
