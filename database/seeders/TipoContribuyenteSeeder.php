<?php

namespace Database\Seeders;

use App\Models\TipoContribuyente;
use Illuminate\Database\Seeder;

class TipoContribuyenteSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            'PERSONA NATURAL CON NEGOCIO',
            'PERSONA JURÍDICA',
            'SOCIEDAD ANÓNIMA CERRADA (SAC)',
            'SOCIEDAD ANÓNIMA ABIERTA (SAA)',
            'SOCIEDAD COMERCIAL DE RESPONSABILIDAD LIMITADA (SRL)',
            'EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA (EIRL)',
            'ASOCIACIÓN',
            'FUNDACIÓN',
            'COOPERATIVA',
            'EMPRESA DEL ESTADO',
            'SUCURSAL DE EMPRESA EXTRANJERA',
            'OTRO',
        ];

        foreach ($tipos as $nombre) {
            TipoContribuyente::updateOrCreate(
                ['nombre' => $nombre],
                ['activo' => true]
            );
        }
    }
}
