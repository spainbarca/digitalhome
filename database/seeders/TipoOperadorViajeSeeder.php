<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoOperadorViajeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            ['nombre' => 'Aerolínea',         'icono' => 'flight',         'descripcion' => 'Compañía de transporte aéreo (LATAM, Sky, JetSmart).'],
            ['nombre' => 'Hotel / Hospedaje', 'icono' => 'hotel',          'descripcion' => 'Hotel, hostal, hospedaje o cadena hotelera.'],
            ['nombre' => 'Empresa de bus',    'icono' => 'directions_bus', 'descripcion' => 'Transporte terrestre (Cruz del Sur, Oltursa, Móvil Tours).'],
            ['nombre' => 'Empresa de tren',   'icono' => 'train',          'descripcion' => 'Operador ferroviario (PeruRail, Inca Rail).'],
            ['nombre' => 'Agencia de viajes', 'icono' => 'travel_explore', 'descripcion' => 'Agencia o tour operador.'],
            ['nombre' => 'Aseguradora',       'icono' => 'health_and_safety','descripcion' => 'Compañía de seguros de viaje / asistencia.'],
            ['nombre' => 'Alquiler de autos', 'icono' => 'car_rental',     'descripcion' => 'Empresa de alquiler de vehículos.'],
            ['nombre' => 'Otro',              'icono' => 'more_horiz',     'descripcion' => 'Otro tipo de operador de viaje.'],
        ];

        foreach ($items as $item) {
            DB::table('tipo_operador_viaje')->insert([
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
