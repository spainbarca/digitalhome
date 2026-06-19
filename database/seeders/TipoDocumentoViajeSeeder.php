<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoDocumentoViajeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            ['nombre' => 'Tarjeta de embarque', 'icono' => 'airplane_ticket',  'descripcion' => 'Boarding pass del vuelo.'],
            ['nombre' => 'E-ticket',            'icono' => 'confirmation_number','descripcion' => 'Boleto electrónico (vuelo, bus o tren).'],
            ['nombre' => 'Voucher de reserva',  'icono' => 'receipt',          'descripcion' => 'Voucher o comprobante de reserva (hotel, tour).'],
            ['nombre' => 'Confirmación',        'icono' => 'mark_email_read',  'descripcion' => 'Correo o documento de confirmación de la reserva.'],
            ['nombre' => 'Itinerario',          'icono' => 'route',            'descripcion' => 'Itinerario de viaje o ruta entregada por el proveedor.'],
            ['nombre' => 'Seguro de viaje',     'icono' => 'health_and_safety','descripcion' => 'Póliza o certificado de seguro de viaje.'],
            ['nombre' => 'Otro',                'icono' => 'more_horiz',       'descripcion' => 'Cualquier otro documento del viaje.'],
        ];

        foreach ($items as $item) {
            DB::table('tipo_documento_viaje')->insert([
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
