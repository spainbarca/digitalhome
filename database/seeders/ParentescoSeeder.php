<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ParentescoSeeder extends Seeder
{
    public function run(): void
    {
        $parentescos = [
            // Cónyuges
            ['nombre' => 'Esposo/a',       'nombre_inverso' => 'Esposo/a',       'grupo' => 'conyuges'],
            ['nombre' => 'Conviviente',     'nombre_inverso' => 'Conviviente',    'grupo' => 'conyuges'],

            // Padres
            ['nombre' => 'Padre',          'nombre_inverso' => 'Hijo/a',         'grupo' => 'padres'],
            ['nombre' => 'Madre',          'nombre_inverso' => 'Hijo/a',         'grupo' => 'padres'],
            ['nombre' => 'Padrastro',      'nombre_inverso' => 'Hijastro/a',     'grupo' => 'padres'],
            ['nombre' => 'Madrastra',      'nombre_inverso' => 'Hijastro/a',     'grupo' => 'padres'],

            // Hijos
            ['nombre' => 'Hijo/a',         'nombre_inverso' => 'Padre/Madre',    'grupo' => 'hijos'],
            ['nombre' => 'Hijastro/a',     'nombre_inverso' => 'Padrastro/Madrastra', 'grupo' => 'hijos'],

            // Hermanos
            ['nombre' => 'Hermano/a',      'nombre_inverso' => 'Hermano/a',      'grupo' => 'hermanos'],
            ['nombre' => 'Hermanastro/a',  'nombre_inverso' => 'Hermanastro/a',  'grupo' => 'hermanos'],

            // Abuelos
            ['nombre' => 'Abuelo/a',       'nombre_inverso' => 'Nieto/a',        'grupo' => 'abuelos'],

            // Nietos
            ['nombre' => 'Nieto/a',        'nombre_inverso' => 'Abuelo/a',       'grupo' => 'nietos'],

            // Tíos
            ['nombre' => 'Tío/a',          'nombre_inverso' => 'Sobrino/a',      'grupo' => 'tios'],

            // Primos
            ['nombre' => 'Primo/a',        'nombre_inverso' => 'Primo/a',        'grupo' => 'primos'],

            // Otros
            ['nombre' => 'Cuñado/a',       'nombre_inverso' => 'Cuñado/a',       'grupo' => 'otros'],
            ['nombre' => 'Suegro/a',       'nombre_inverso' => 'Yerno/Nuera',    'grupo' => 'otros'],
            ['nombre' => 'Yerno/Nuera',    'nombre_inverso' => 'Suegro/a',       'grupo' => 'otros'],
            ['nombre' => 'Sobrino/a',      'nombre_inverso' => 'Tío/a',          'grupo' => 'otros'],
            ['nombre' => 'Otro',           'nombre_inverso' => null,             'grupo' => 'otros'],
        ];

        foreach ($parentescos as $p) {
            DB::table('parentescos')->insertOrIgnore([
                'id'             => Str::uuid(),
                'nombre'         => $p['nombre'],
                'nombre_inverso' => $p['nombre_inverso'],
                'grupo'          => $p['grupo'],
                'activo'         => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
