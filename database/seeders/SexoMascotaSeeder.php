<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SexoMascotaSeeder extends Seeder
{
    public function run(): void
    {
        $tabla = 'sexo_mascota';
        $ids = DB::table($tabla)->pluck('id', 'nombre');

        $items = [
            ['nombre' => 'Macho',  'icono' => 'male'],
            ['nombre' => 'Hembra', 'icono' => 'female'],
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
