<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoEntidadFinancieraSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Banco',                     'icono' => 'account_balance',     'descripcion' => 'Entidad bancaria regulada por la SBS.'],
            ['nombre' => 'Financiera',                'icono' => 'business_center',     'descripcion' => 'Empresa financiera no bancaria.'],
            ['nombre' => 'Caja Municipal',            'icono' => 'store',               'descripcion' => 'Caja municipal de ahorro y crédito.'],
            ['nombre' => 'Caja Rural',                'icono' => 'cottage',             'descripcion' => 'Caja rural de ahorro y crédito.'],
            ['nombre' => 'Cooperativa',               'icono' => 'group',               'descripcion' => 'Cooperativa de ahorro y crédito.'],
            ['nombre' => 'AFP',                       'icono' => 'savings',             'descripcion' => 'Administradora de Fondos de Pensiones.'],
            ['nombre' => 'Seguro',                    'icono' => 'shield',              'descripcion' => 'Empresa aseguradora.'],
            ['nombre' => 'Billetera Digital',         'icono' => 'wallet',              'descripcion' => 'Plataforma de pagos y billetera electrónica.'],
            ['nombre' => 'Otro',                      'icono' => 'more_horiz',          'descripcion' => 'Otro tipo de entidad financiera.'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_entidad_financiera')->insert([
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
