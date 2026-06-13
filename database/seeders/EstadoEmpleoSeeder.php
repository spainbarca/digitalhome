<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EstadoEmpleoSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $estados = [
            // [nombre, descripcion, icono, color]
            ['Activo', 'Empleo actual en curso', 'work', 'green'],
            ['Finalizado', 'Vínculo laboral terminado por fin de contrato', 'check_circle', 'gray'],
            ['Renuncia', 'Cese por renuncia voluntaria', 'logout', 'orange'],
            ['Despido', 'Cese por decisión del empleador', 'cancel', 'red'],
            ['Cese por mutuo acuerdo', 'Término del vínculo por acuerdo entre las partes', 'handshake', 'blue'],
            ['Suspendido', 'Vínculo laboral suspendido temporalmente', 'pause_circle', 'yellow'],
            ['En periodo de prueba', 'Dentro del periodo de prueba inicial', 'hourglass_top', 'indigo'],
            ['Práctica concluida', 'Prácticas finalizadas', 'school', 'gray'],
        ];

        foreach ($estados as $e) {
            DB::table('estado_empleo')->insert([
                'id'          => Str::uuid(),
                'nombre'      => $e[0],
                'descripcion' => $e[1],
                'icono'       => $e[2],
                'color'       => $e[3],
                'activo'      => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }
}
