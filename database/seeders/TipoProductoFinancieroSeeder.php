<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoProductoFinancieroSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Cuenta de Ahorros',    'naturaleza' => 'activo',  'icono' => 'savings',          'descripcion' => 'Cuenta de depósito de libre disponibilidad.'],
            ['nombre' => 'Cuenta Corriente',     'naturaleza' => 'activo',  'icono' => 'account_balance',  'descripcion' => 'Cuenta corriente con chequera asociada.'],
            ['nombre' => 'Cuenta Sueldo',           'naturaleza' => 'activo', 'icono' => 'payments', 'descripcion' => 'Para pagos de sueldo'],
            ['nombre' => 'CTS',                  'naturaleza' => 'activo',  'icono' => 'work_history',     'descripcion' => 'Compensación por Tiempo de Servicio.'],
            ['nombre' => 'Fondo AFP',            'naturaleza' => 'activo',  'icono' => 'elderly',          'descripcion' => 'Fondo de pensiones administrado por AFP.'],
            ['nombre' => 'Plazo Fijo',           'naturaleza' => 'activo',  'icono' => 'lock_clock',       'descripcion' => 'Depósito a plazo fijo con fecha de vencimiento.'],
            ['nombre' => 'Tarjeta de Débito',    'naturaleza' => 'activo',  'icono' => 'credit_card',      'descripcion' => 'Tarjeta asociada a una cuenta de ahorros o corriente.'],
            ['nombre' => 'Tarjeta de Crédito',   'naturaleza' => 'pasivo',  'icono' => 'credit_score',     'descripcion' => 'Línea de crédito rotativa con fecha de corte y pago.'],
            ['nombre' => 'Préstamo Personal',    'naturaleza' => 'pasivo',  'icono' => 'handshake',        'descripcion' => 'Crédito personal con cuotas fijas.'],
            ['nombre' => 'Préstamo Hipotecario', 'naturaleza' => 'pasivo',  'icono' => 'home_work',        'descripcion' => 'Crédito hipotecario para adquisición de inmueble.'],
            ['nombre' => 'Préstamo Vehicular',   'naturaleza' => 'pasivo',  'icono' => 'directions_car',   'descripcion' => 'Crédito para adquisición de vehículo.'],
            ['nombre' => 'Seguro',               'naturaleza' => null,      'icono' => 'shield',           'descripcion' => 'Póliza de seguro de vida, salud o propiedad.'],
            ['nombre' => 'Fondo Mutuo',             'naturaleza' => 'activo', 'icono' => 'trending_up', 'descripcion' => 'Cuenta perteneciente a mas de un individuo'],
            ['nombre' => 'Otro',                 'naturaleza' => null,      'icono' => 'more_horiz',       'descripcion' => 'Otro tipo de producto financiero.'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_producto_financiero')->insert([
                'id'          => (string) Str::uuid(),
                'nombre'      => $tipo['nombre'],
                'naturaleza'  => $tipo['naturaleza'],
                'icono'       => $tipo['icono'],
                'descripcion' => $tipo['descripcion'],
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
