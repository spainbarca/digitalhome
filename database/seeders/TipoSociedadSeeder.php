<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoSociedadSeeder extends Seeder
{
    public function run(): void
    {
        $tabla = 'tipo_sociedad';
        $ids = DB::table($tabla)->pluck('id', 'nombre');

        $items = [
            ['sigla' => null,        'nombre' => 'Persona Natural con Negocio',                    'icono' => 'person'],
            ['sigla' => 'E.I.R.L.',  'nombre' => 'Empresa Individual de Responsabilidad Limitada', 'icono' => 'badge'],
            ['sigla' => 'S.R.L.',    'nombre' => 'Sociedad Comercial de Responsabilidad Limitada', 'icono' => 'groups'],
            ['sigla' => 'S.A.C.',    'nombre' => 'Sociedad Anónima Cerrada',                       'icono' => 'business'],
            ['sigla' => 'S.A.',      'nombre' => 'Sociedad Anónima',                               'icono' => 'corporate_fare'],
            ['sigla' => 'S.A.A.',    'nombre' => 'Sociedad Anónima Abierta',                       'icono' => 'domain'],
            ['sigla' => 'S. Civil',  'nombre' => 'Sociedad Civil',                                 'icono' => 'handshake'],
        ];

        foreach ($items as $i => $item) {
            DB::table($tabla)->updateOrInsert(
                ['nombre' => $item['nombre']],
                [
                    'id'         => $ids[$item['nombre']] ?? (string) Str::uuid(),
                    'sigla'      => $item['sigla'],
                    'icono'      => $item['icono'],
                    'activo'     => true,
                    'orden'      => $i + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
