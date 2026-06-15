<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoTransaccionSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Depósito',               'naturaleza' => 'ingreso', 'color' => '#22c55e', 'icono' => 'south_west',           'descripcion' => 'Ingreso de dinero a una cuenta.'],
            ['nombre' => 'Retiro',                 'naturaleza' => 'egreso',  'color' => '#dc2626', 'icono' => 'north_east',           'descripcion' => 'Extracción de dinero de una cuenta.'],
            ['nombre' => 'Transferencia Enviada',  'naturaleza' => 'egreso',  'color' => '#dc2626', 'icono' => 'north_east',           'descripcion' => 'Transferencia de dinero a otra cuenta.'],
            ['nombre' => 'Transferencia Recibida', 'naturaleza' => 'ingreso', 'color' => '#22c55e', 'icono' => 'south_west',           'descripcion' => 'Recepción de transferencia de otra cuenta.'],
            ['nombre' => 'Pago de Servicio',       'naturaleza' => 'egreso',  'color' => '#dc2626', 'icono' => 'receipt',              'descripcion' => 'Pago de servicios (luz, agua, internet, etc.).'],
            ['nombre' => 'Pago de Tarjeta',        'naturaleza' => 'neutro',  'color' => '#3b82f6', 'icono' => 'credit_card_off',      'descripcion' => 'Pago de saldo de tarjeta de crédito.'],
            ['nombre' => 'Pago de Cuota',          'naturaleza' => 'egreso',  'color' => '#dc2626', 'icono' => 'payments',             'descripcion' => 'Pago de cuota de préstamo o crédito.'],
            ['nombre' => 'Abono de Sueldo',        'naturaleza' => 'ingreso', 'color' => '#22c55e', 'icono' => 'account_balance_wallet','descripcion' => 'Abono de remuneración o sueldo.'],
            ['nombre' => 'Depósito CTS',           'naturaleza' => 'ingreso', 'color' => '#22c55e', 'icono' => 'savings',              'descripcion' => 'Depósito semestral de CTS.'],
            ['nombre' => 'Aporte AFP/ONP',         'naturaleza' => 'egreso',  'color' => '#6b7280', 'icono' => 'elderly',              'descripcion' => 'Aporte previsional (AFP u ONP).'],
            ['nombre' => 'Interés Ganado',         'naturaleza' => 'ingreso', 'color' => '#22c55e', 'icono' => 'trending_up',          'descripcion' => 'Abono de intereses generados por la cuenta.'],
            ['nombre' => 'Cargo por Comisión',     'naturaleza' => 'egreso',  'color' => '#f97316', 'icono' => 'money_off',            'descripcion' => 'Cargo de comisión o mantenimiento.'],
            ['nombre' => 'ITF',                    'naturaleza' => 'egreso',  'color' => '#f97316', 'icono' => 'receipt_long',         'descripcion' => 'Impuesto a las Transacciones Financieras.'],
            ['nombre' => 'Otro',                   'naturaleza' => 'neutro',  'color' => '#6b7280', 'icono' => 'more_horiz',           'descripcion' => 'Otro tipo de movimiento o transacción.'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_transaccion')->insert([
                'id'          => (string) Str::uuid(),
                'nombre'      => $tipo['nombre'],
                'naturaleza'  => $tipo['naturaleza'],
                'color'       => $tipo['color'],
                'icono'       => $tipo['icono'],
                'descripcion' => $tipo['descripcion'],
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
