<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoTransporteSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            ['nombre' => 'Avión',        'icono' => 'flight',          'descripcion' => 'Transporte aéreo (vuelos nacionales o internacionales).'],
            ['nombre' => 'Bus',          'icono' => 'directions_bus',  'descripcion' => 'Transporte terrestre interprovincial o urbano.'],
            ['nombre' => 'Tren',         'icono' => 'train',           'descripcion' => 'Transporte ferroviario (ej. PeruRail, Inca Rail).'],
            ['nombre' => 'Auto',         'icono' => 'directions_car',  'descripcion' => 'Vehículo propio o alquilado.'],
            ['nombre' => 'Ferry / Bote', 'icono' => 'directions_boat', 'descripcion' => 'Transporte fluvial o marítimo.'],
            ['nombre' => 'Taxi / App',   'icono' => 'local_taxi',      'descripcion' => 'Taxi, Uber, InDrive u otra app de movilidad.'],
            ['nombre' => 'Otro',         'icono' => 'more_horiz',      'descripcion' => 'Otro medio de transporte.'],
        ];

        foreach ($items as $item) {
            DB::table('tipo_transporte')->insert([
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
