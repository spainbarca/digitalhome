<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoServicioSeeder extends Seeder
{
   public function run(): void
    {
        $tipos = [
            ['nombre' => 'Electricidad', 'icono' => 'zap'],
            ['nombre' => 'Agua',         'icono' => 'droplets'],
            ['nombre' => 'Gas',          'icono' => 'flame'],
            ['nombre' => 'Teléfono',     'icono' => 'phone'],
            ['nombre' => 'Internet',     'icono' => 'wifi'],
            ['nombre' => 'Cable',        'icono' => 'tv'],
            ['nombre' => 'Otro',         'icono' => 'circle-ellipsis'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_servicio')->insert([
                'id'         => Str::uuid(),
                'nombre'     => $tipo['nombre'],
                'icono'      => $tipo['icono'],
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
