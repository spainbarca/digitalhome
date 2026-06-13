<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoBeneficioSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $beneficios = [
            // [nombre, descripcion, icono]
            ['Seguro médico (EPS)', 'Entidad Prestadora de Salud complementaria', 'health_and_safety'],
            ['EsSalud', 'Seguro social de salud estatal', 'local_hospital'],
            ['Seguro de vida ley', 'Seguro de vida obligatorio por ley', 'shield'],
            ['Seguro privado de salud', 'Seguro de salud particular', 'medical_services'],
            ['AFP', 'Administradora de Fondos de Pensiones', 'savings'],
            ['ONP', 'Oficina de Normalización Previsional', 'account_balance'],
            ['CTS', 'Compensación por Tiempo de Servicios', 'account_balance_wallet'],
            ['Gratificación', 'Gratificación de julio y diciembre', 'redeem'],
            ['Asignación familiar', 'Asignación familiar por hijos', 'family_restroom'],
            ['Utilidades', 'Participación en las utilidades de la empresa', 'paid'],
            ['Bono de productividad', 'Bono por cumplimiento de metas', 'trending_up'],
            ['Vales de alimentación', 'Vales o tarjeta de alimentación', 'restaurant'],
            ['Movilidad', 'Subsidio o bono de movilidad', 'directions_bus'],
            ['Vacaciones', 'Derecho a descanso vacacional remunerado', 'beach_access'],
            ['Capacitación pagada', 'Capacitaciones financiadas por el empleador', 'school'],
            ['Seguro dental', 'Cobertura dental', 'dentistry'],
            ['Seguro de visión', 'Cobertura oftalmológica', 'visibility'],
            ['Gimnasio', 'Membresía o subsidio de gimnasio', 'fitness_center'],
            ['Trabajo remoto', 'Beneficio de trabajo desde casa', 'home_work'],
            ['Otro', 'Beneficio no categorizado', 'more_horiz'],
        ];

        foreach ($beneficios as $b) {
            DB::table('tipo_beneficio')->insert([
                'id'          => Str::uuid(),
                'nombre'      => $b[0],
                'descripcion' => $b[1],
                'icono'       => $b[2],
                'activo'      => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }
}
