<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->foreignUuid('producto_financiero_id')->nullable()->after('comercio_id')
                ->constrained('productos_financieros')
                ->comment('Tarjeta/cuenta usada en la compra (decisión #2: gasto granular vive en Compras)');
        });
    }

    public function down(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->dropForeign(['producto_financiero_id']);
            $table->dropColumn('producto_financiero_id');
        });
    }
};
