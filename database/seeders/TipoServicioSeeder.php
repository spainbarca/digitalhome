<?php

namespace Database\Seeders;

use App\Models\TipoServicio;
use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;

class TipoServicioSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Electricidad',      'icono' => 'bolt',                'unidad' => 'Kilovatio-hora'],
            ['nombre' => 'Agua y Saneamiento', 'icono' => 'water_drop',          'unidad' => 'Metro cúbico'],
            ['nombre' => 'Gas Natural',        'icono' => 'local_fire_department','unidad' => 'Metro cúbico'],
            ['nombre' => 'Internet',           'icono' => 'wifi',                'unidad' => 'Megabit por segundo'],
            ['nombre' => 'Telefonía Fija',     'icono' => 'phone',               'unidad' => 'Minuto'],
            ['nombre' => 'Telefonía Móvil',    'icono' => 'smartphone',          'unidad' => 'Minuto'],
            ['nombre' => 'Cable/TV',           'icono' => 'tv',                  'unidad' => 'Canal'],
            ['nombre' => 'Seguridad',          'icono' => 'security',            'unidad' => 'Dispositivo'],
            ['nombre' => 'Otro',               'icono' => 'home_repair_service', 'unidad' => 'Unidad'],
        ];

        foreach ($tipos as $datos) {
            $unidadId = UnidadMedida::where('nombre', $datos['unidad'])->value('id');

            TipoServicio::updateOrCreate(
                ['nombre' => $datos['nombre']],
                [
                    'icono'            => $datos['icono'],
                    'unidad_medida_id' => $unidadId,
                    'activo'           => true,
                ]
            );
        }
    }
}
