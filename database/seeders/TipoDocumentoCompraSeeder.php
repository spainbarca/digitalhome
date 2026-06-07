<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoDocumentoCompraSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Boleta',              'icono' => 'receipt_long',        'descripcion' => 'Boleta de venta del comercio.'],
            ['nombre' => 'Factura',             'icono' => 'request_quote',       'descripcion' => 'Factura con datos fiscales del comprador.'],
            ['nombre' => 'Garantía',            'icono' => 'verified_user',       'descripcion' => 'Certificado o documento de garantía del producto.'],
            ['nombre' => 'Manual',              'icono' => 'menu_book',           'descripcion' => 'Manual de uso o instrucciones del producto.'],
            ['nombre' => 'Comprobante de pago', 'icono' => 'payments',            'descripcion' => 'Voucher, transferencia u otro comprobante de pago.'],
            ['nombre' => 'Ticket',              'icono' => 'confirmation_number', 'descripcion' => 'Ticket o nota de venta simple.'],
            ['nombre' => 'Otro',                'icono' => 'more_horiz',          'descripcion' => 'Cualquier otro documento relacionado a la compra.'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_documento_compra')->insert([
                'id'          => Str::uuid(),
                'nombre'      => $tipo['nombre'],
                'icono'       => $tipo['icono'],
                'descripcion' => $tipo['descripcion'],
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
