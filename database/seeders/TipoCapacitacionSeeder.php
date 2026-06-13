<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoCapacitacionSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $tipos = [
            // [nombre, descripcion, icono]
            ['Curso', 'Curso de corta o mediana duración', 'menu_book'],
            ['Taller', 'Taller práctico', 'build'],
            ['Seminario', 'Seminario o conferencia', 'co_present'],
            ['Webinar', 'Seminario en línea', 'videocam'],
            ['Diplomado', 'Programa de especialización', 'workspace_premium'],
            ['Certificación', 'Certificación profesional acreditada', 'verified'],
            ['Especialización', 'Programa de especialización avanzada', 'star'],
            ['Maestría', 'Estudios de posgrado de maestría', 'school'],
            ['Doctorado', 'Estudios de posgrado de doctorado', 'history_edu'],
            ['Bootcamp', 'Programa intensivo de formación', 'rocket_launch'],
            ['Charla', 'Charla o ponencia', 'record_voice_over'],
            ['Congreso', 'Congreso o convención', 'groups'],
            ['Curso online', 'Curso autodidacta en plataforma virtual', 'computer'],
            ['Capacitación interna', 'Capacitación brindada por el empleador', 'business_center'],
            ['Idiomas', 'Curso o certificación de idiomas', 'translate'],
            ['Otro', 'Tipo de capacitación no categorizado', 'more_horiz'],
        ];

        foreach ($tipos as $t) {
            DB::table('tipo_capacitacion')->insert([
                'id'          => Str::uuid(),
                'nombre'      => $t[0],
                'descripcion' => $t[1],
                'icono'       => $t[2],
                'activo'      => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }
}
