<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EspecieSeeder extends Seeder
{
    public function run(): void
    {
        $tabla = 'especies';
        $ids = DB::table($tabla)->pluck('id', 'nombre');

        $items = [
            ['nombre' => 'Perro',    'icono' => 'pets'],
            ['nombre' => 'Gato',     'icono' => 'pets'],
            ['nombre' => 'Ave',      'icono' => 'pets'],
            ['nombre' => 'Conejo',   'icono' => 'pets'],
            ['nombre' => 'Hámster',  'icono' => 'pets'],
            ['nombre' => 'Pez',      'icono' => 'pets'],
            ['nombre' => 'Tortuga',  'icono' => 'pets'],
        ];

        foreach ($items as $item) {
            DB::table($tabla)->updateOrInsert(
                ['nombre' => $item['nombre']],
                [
                    'id'         => $ids[$item['nombre']] ?? (string) Str::uuid(),
                    'icono'      => $item['icono'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
