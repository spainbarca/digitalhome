<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EstadoMascotaSeeder extends Seeder
{
    public function run(): void
    {
        $tabla = 'estado_mascota';
        $ids = DB::table($tabla)->pluck('id', 'nombre');

        $items = [
            ['nombre' => 'Activo',     'icono' => 'check_circle',               'color' => '#16a34a'],
            ['nombre' => 'Fallecido',  'icono' => 'sentiment_very_dissatisfied','color' => '#6b7280'],
            ['nombre' => 'Perdido',    'icono' => 'help',                       'color' => '#f59e0b'],
            ['nombre' => 'Entregado',  'icono' => 'swap_horiz',                 'color' => '#3b82f6'],
        ];

        foreach ($items as $item) {
            DB::table($tabla)->updateOrInsert(
                ['nombre' => $item['nombre']],
                [
                    'id'         => $ids[$item['nombre']] ?? (string) Str::uuid(),
                    'icono'      => $item['icono'],
                    'color'      => $item['color'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
