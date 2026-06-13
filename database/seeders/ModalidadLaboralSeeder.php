<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ModalidadLaboralSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $modalidades = [
            // [nombre, descripcion, icono]
            ['Dependiente (planilla)', 'Trabajo en relación de dependencia bajo planilla', 'badge'],
            ['Plazo fijo', 'Contrato sujeto a modalidad con fecha de término', 'event_busy'],
            ['Plazo indeterminado', 'Contrato a plazo indefinido', 'all_inclusive'],
            ['Locación de servicios', 'Servicios bajo recibo por honorarios (cuarta categoría)', 'receipt'],
            ['Practicante pre-profesional', 'Prácticas pre-profesionales durante estudios', 'school'],
            ['Practicante profesional', 'Prácticas profesionales tras egresar', 'cast_for_education'],
            ['CAS', 'Contrato Administrativo de Servicios (sector público)', 'account_balance'],
            ['Régimen agrario', 'Trabajo bajo régimen laboral agrario', 'agriculture'],
            ['Microempresa (REMYPE)', 'Régimen laboral especial de microempresa', 'storefront'],
            ['Pequeña empresa (REMYPE)', 'Régimen laboral especial de pequeña empresa', 'store'],
            ['Part-time', 'Jornada parcial (menos de 4 horas diarias)', 'schedule'],
            ['Tiempo completo', 'Jornada completa', 'work'],
            ['Teletrabajo', 'Trabajo remoto desde casa', 'home_work'],
            ['Freelance', 'Trabajo independiente por proyectos', 'computer'],
            ['Voluntariado', 'Labor voluntaria sin vínculo laboral remunerado', 'volunteer_activism'],
            ['Otro', 'Modalidad no categorizada', 'more_horiz'],
        ];

        foreach ($modalidades as $m) {
            DB::table('modalidad_laboral')->insert([
                'id'          => Str::uuid(),
                'nombre'      => $m[0],
                'descripcion' => $m[1],
                'icono'       => $m[2],
                'activo'      => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }
}
