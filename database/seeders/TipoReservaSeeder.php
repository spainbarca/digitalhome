<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoReservaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            ['nombre' => 'Vuelo',               'icono' => 'flight_takeoff',  'descripcion' => 'Reserva de pasaje aéreo.'],
            ['nombre' => 'Hospedaje',           'icono' => 'hotel',           'descripcion' => 'Reserva de hotel, hostal o alojamiento.'],
            ['nombre' => 'Transporte terrestre','icono' => 'directions_bus',  'descripcion' => 'Reserva de bus, tren u otro transporte por tierra.'],
            ['nombre' => 'Tour / Actividad',    'icono' => 'tour',            'descripcion' => 'Reserva de tour, excursión o actividad.'],
            ['nombre' => 'Traslado',            'icono' => 'airport_shuttle', 'descripcion' => 'Traslado o transfer (aeropuerto, hotel).'],
            ['nombre' => 'Alquiler de vehículo','icono' => 'car_rental',      'descripcion' => 'Reserva de alquiler de auto u otro vehículo.'],
            ['nombre' => 'Otro',                'icono' => 'more_horiz',      'descripcion' => 'Cualquier otra reserva.'],
        ];

        foreach ($items as $item) {
            DB::table('tipo_reserva')->insert([
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
