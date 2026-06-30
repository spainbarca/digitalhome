<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrigenMascotaSeeder extends Seeder
{
    public function run(): void
    {
        $tabla = 'origen_mascota';
        $ids = DB::table($tabla)->pluck('id', 'nombre');

        $items = [
            ['nombre' => 'Adopción',          'icono' => 'volunteer_activism'],
            ['nombre' => 'Compra',            'icono' => 'shopping_bag'],
            ['nombre' => 'Rescate',           'icono' => 'health_and_safety'],
            ['nombre' => 'Nacimiento en casa','icono' => 'home'],
            ['nombre' => 'Regalo',            'icono' => 'redeem'],
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
