<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoDocumentoNegocioSeeder extends Seeder
{
    public function run(): void
    {
        $tabla = 'tipo_documento_negocio';
        $ids = DB::table($tabla)->pluck('id', 'nombre');

        // Catálogo único y plano: conviven las 3 familias (negocio / pedido / pago).
        $items = [
            // Documentos propios del negocio
            ['nombre' => 'Ficha RUC',                     'icono' => 'badge'],
            ['nombre' => 'Licencia de Funcionamiento',    'icono' => 'storefront'],
            ['nombre' => 'Certificado de Defensa Civil',  'icono' => 'verified_user'],
            ['nombre' => 'Partida Registral (SUNARP)',    'icono' => 'menu_book'],
            ['nombre' => 'Registro de Marca (INDECOPI)',  'icono' => 'workspace_premium'],
            ['nombre' => 'Permiso Sectorial',             'icono' => 'fact_check'],
            ['nombre' => 'Vigencia de Poder',             'icono' => 'gavel'],
            // Comprobantes de compra (cuelgan del pedido)
            ['nombre' => 'Boleta de Venta',               'icono' => 'receipt'],
            ['nombre' => 'Factura',                       'icono' => 'receipt_long'],
            ['nombre' => 'Guía de Remisión',              'icono' => 'local_shipping'],
            ['nombre' => 'Nota de Crédito',               'icono' => 'credit_card_off'],
            // Documentos de pago institucional (cuelgan del pago)
            ['nombre' => 'Notificación',                  'icono' => 'notifications'],
            ['nombre' => 'Constancia de Pago',            'icono' => 'paid'],
            ['nombre' => 'Orden de Pago',                 'icono' => 'request_quote'],
            ['nombre' => 'Resolución',                    'icono' => 'description'],
            // Genérico
            ['nombre' => 'Otro',                          'icono' => 'attach_file'],
        ];

        foreach ($items as $i => $item) {
            DB::table($tabla)->updateOrInsert(
                ['nombre' => $item['nombre']],
                [
                    'id'         => $ids[$item['nombre']] ?? (string) Str::uuid(),
                    'icono'      => $item['icono'],
                    'activo'     => true,
                    'orden'      => $i + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
