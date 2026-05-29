<?php

namespace Database\Seeders;

use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;

class UnidadMedidaSeeder extends Seeder
{
    public function run(): void
    {
        $unidades = [
            ['nombre' => 'Kilovatio-hora',      'simbolo' => 'kWh',  'icono' => 'bolt'],
            ['nombre' => 'Metro cúbico',         'simbolo' => 'm³',   'icono' => 'water_drop'],
            ['nombre' => 'Megabit por segundo',  'simbolo' => 'Mbps', 'icono' => 'wifi'],
            ['nombre' => 'Minuto',               'simbolo' => 'min',  'icono' => 'phone'],
            ['nombre' => 'Canal',                'simbolo' => 'ch',   'icono' => 'tv'],
            ['nombre' => 'Dispositivo',          'simbolo' => 'disp', 'icono' => 'security'],
            ['nombre' => 'Unidad',               'simbolo' => 'u',    'icono' => 'category'],
        ];

        foreach ($unidades as $datos) {
            UnidadMedida::updateOrCreate(
                ['nombre' => $datos['nombre']],
                array_merge($datos, ['activo' => true])
            );
        }
    }
}
