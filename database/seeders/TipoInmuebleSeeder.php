<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoInmuebleSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Casa',          'icono' => 'home'],
            ['nombre' => 'Departamento',  'icono' => 'building'],
            ['nombre' => 'Oficina',       'icono' => 'briefcase'],
            ['nombre' => 'Local',         'icono' => 'store'],
            ['nombre' => 'Terreno',       'icono' => 'map'],
            ['nombre' => 'Cochera',       'icono' => 'car'],
            ['nombre' => 'Otro',          'icono' => 'file'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_inmueble')->insert([
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
