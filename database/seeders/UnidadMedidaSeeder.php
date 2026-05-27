<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UnidadMedidaSeeder extends Seeder
{
    public function run(): void
    {
        $unidades = [
            ['nombre' => 'Kilovatio hora', 'simbolo' => 'kWh'],   // Electricidad
            ['nombre' => 'Metro cúbico',   'simbolo' => 'm³'],    // Agua y Gas
            ['nombre' => 'Galón',          'simbolo' => 'gal'],   // Agua (algunos casos)
            ['nombre' => 'Litro',          'simbolo' => 'L'],
        ];

        foreach ($unidades as $unidad) {
            DB::table('unidad_medida')->insert([
                'id'         => Str::uuid(),
                'nombre'     => $unidad['nombre'],
                'simbolo'    => $unidad['simbolo'],
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
