<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TiposCentroMedicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Hospital',             'icono' => 'local_hospital'],
            ['nombre' => 'Clínica',              'icono' => 'clinic'],
            ['nombre' => 'Consultorio',          'icono' => 'meeting_room'],
            ['nombre' => 'Centro de Salud',      'icono' => 'health_and_safety'],
            ['nombre' => 'Posta Médica',         'icono' => 'cottage'],
            ['nombre' => 'Farmacia',             'icono' => 'local_pharmacy'],
            ['nombre' => 'Laboratorio',          'icono' => 'biotech'],
            ['nombre' => 'Centro de Imagen',     'icono' => 'radiology'],
            ['nombre' => 'Centro Odontológico',  'icono' => 'dentistry'],
            ['nombre' => 'Centro Óptico',        'icono' => 'visibility'],
            ['nombre' => 'Centro de Salud Mental','icono' => 'psychology'],
            ['nombre' => 'Centro de Rehabilitación', 'icono' => 'physical_therapy'],
            ['nombre' => 'Telemedicina',         'icono' => 'video_call'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipos_centro_medico')->insertOrIgnore([
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
