<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoEntidadFinancieraSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $tipos = [
            ['nombre' => 'Banco',            'descripcion' => 'Banco múltiple supervisado por la SBS',        'icono' => 'account_balance'],
            ['nombre' => 'Financiera',       'descripcion' => 'Empresa financiera',                            'icono' => 'request_quote'],
            ['nombre' => 'Caja Municipal',   'descripcion' => 'Caja Municipal de Ahorro y Crédito (CMAC)',      'icono' => 'savings'],
            ['nombre' => 'Caja Rural',       'descripcion' => 'Caja Rural de Ahorro y Crédito (CRAC)',          'icono' => 'agriculture'],
            ['nombre' => 'Cooperativa',      'descripcion' => 'Cooperativa de ahorro y crédito',               'icono' => 'groups'],
            ['nombre' => 'AFP',              'descripcion' => 'Administradora de Fondos de Pensiones',          'icono' => 'elderly'],
            ['nombre' => 'Billetera Digital','descripcion' => 'Billetera o monedero electrónico (Yape, Plin)',  'icono' => 'account_balance_wallet'],
        ];

        foreach ($tipos as $t) {
            DB::table('tipo_entidad_financiera')->insert(array_merge($t, [
                'activo' => true, 'created_at' => $now, 'updated_at' => $now,
            ]));
        }
    }
}
