<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'centros_medicos'          => 'nombre_referencial',
            'comercios'                => 'nombre_referencial',
            'empleadores'              => 'nombre',
            'entidades_legales'        => 'nombre',
            'instituciones_educativas' => 'nombre_referencial',
            'proveedores_servicio'     => 'nombre_comercial',
        ];

        foreach ($tables as $table => $after) {
            Schema::table($table, function (Blueprint $table) use ($after) {
                $table->string('sigla', 50)->nullable()->after($after);
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'centros_medicos',
            'comercios',
            'empleadores',
            'entidades_legales',
            'instituciones_educativas',
            'proveedores_servicio',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('sigla');
            });
        }
    }
};
