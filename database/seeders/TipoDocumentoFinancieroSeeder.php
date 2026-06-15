<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoDocumentoFinancieroSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Estado de Cuenta',    'icono' => 'receipt_long',    'descripcion' => 'Estado de cuenta mensual emitido por la entidad financiera.'],
            ['nombre' => 'Cronograma de Pagos', 'icono' => 'calendar_month',  'descripcion' => 'Tabla de amortización con cuotas y fechas de vencimiento.'],
            ['nombre' => 'Voucher de Pago',     'icono' => 'payments',        'descripcion' => 'Comprobante de pago o transferencia.'],
            ['nombre' => 'Contrato',            'icono' => 'description',     'descripcion' => 'Contrato de apertura o formalización del producto.'],
            ['nombre' => 'Extracto',            'icono' => 'summarize',       'descripcion' => 'Extracto o movimientos de cuenta en un período.'],
            ['nombre' => 'Póliza de Seguro',    'icono' => 'shield',          'descripcion' => 'Póliza o certificado de seguro vigente.'],
            ['nombre' => 'Constancia',                'descripcion' => 'Constancia o certificado del producto',      'icono' => 'verified'],
            ['nombre' => 'Voucher de Operación',      'descripcion' => 'Comprobante de transferencia/pago',          'icono' => 'receipt'],
            ['nombre' => 'Designación de Beneficiarios','descripcion' => 'Documento de beneficiarios (CTS/AFP/plazo)','icono' => 'group_add'],
            ['nombre' => 'Cláusula de Seguro',        'descripcion' => 'Desgravamen u otro seguro asociado',         'icono' => 'shield'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_documento_financiero')->insert([
                'id'          => (string) Str::uuid(),
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
