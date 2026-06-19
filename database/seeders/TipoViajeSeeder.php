<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoViajeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            ['nombre' => 'Turismo',   'icono' => 'beach_access',   'descripcion' => 'Viaje de placer, vacaciones o recreación.'],
            ['nombre' => 'Trabajo',   'icono' => 'work',           'descripcion' => 'Viaje por motivos laborales o de negocios.'],
            ['nombre' => 'Familiar',  'icono' => 'family_restroom', 'descripcion' => 'Visita a familiares o evento familiar.'],
            ['nombre' => 'Estudios',  'icono' => 'school',         'descripcion' => 'Viaje por motivos académicos o de formación.'],
            ['nombre' => 'Salud',     'icono' => 'local_hospital', 'descripcion' => 'Viaje por atención médica o tratamiento.'],
            ['nombre' => 'Otro',      'icono' => 'more_horiz',     'descripcion' => 'Cualquier otro motivo de viaje.'],
        ];

        foreach ($items as $item) {
            DB::table('tipo_viaje')->insert([
                'id'          => (string) Str::uuid(),
                'nombre'      => $item['nombre'],
                'icono'       => $item['icono'],
                'descripcion' => $item['descripcion'],
                'activo'      => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }
}
