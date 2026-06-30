<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoEventoSanitarioSeeder extends Seeder
{
    public function run(): void
    {
        $tabla = 'tipo_evento_sanitario';
        $ids = DB::table($tabla)->pluck('id', 'nombre');

        $items = [
            ['nombre' => 'Vacunación',                 'icono' => 'vaccines',      'color' => '#3b82f6'],
            ['nombre' => 'Desparasitación interna',    'icono' => 'medication',    'color' => '#f59e0b'],
            ['nombre' => 'Desparasitación externa',    'icono' => 'pest_control',  'color' => '#8b5cf6'],
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
