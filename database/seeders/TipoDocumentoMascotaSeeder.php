<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoDocumentoMascotaSeeder extends Seeder
{
    public function run(): void
    {
        $tabla = 'tipo_documento_mascota';
        $ids = DB::table($tabla)->pluck('id', 'nombre');

        $items = [
            ['nombre' => 'Carnet de vacunación',         'icono' => 'badge'],
            ['nombre' => 'Certificado de salud',         'icono' => 'health_and_safety'],
            ['nombre' => 'Pedigree / Certificado de raza', 'icono' => 'workspace_premium'],
            ['nombre' => 'Ficha de esterilización',      'icono' => 'content_cut'],
            ['nombre' => 'Resultado de laboratorio',     'icono' => 'science'],
            ['nombre' => 'Receta veterinaria',           'icono' => 'description'],
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
