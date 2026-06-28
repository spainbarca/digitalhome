<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoDocumentoLegalRelevanteNegocioSeeder extends Seeder
{
    public function run(): void
    {
        // Marca los tipos de documento LEGAL que deben "asomar" en el módulo Negocios
        // (sección "Documentos legales relevantes" del show del negocio).
        //
        // IMPORTANTE: ajusta estos nombres a los EXACTOS de tu catálogo tipo_documento_legal.
        // Si un nombre no calza, esa fila simplemente no se marca (sin error).
        $relevantes = [
            'Escritura Pública de Constitución',
            'Minuta de Constitución',
            'Estatuto Social',
            'Vigencia de Poder',
            'Poder',
            'Contrato',
        ];

        // No destructivo: solo enciende los listados (no apaga los que marcaste a mano).
        $marcados = DB::table('tipo_documento_legal')
            ->whereIn('nombre', $relevantes)
            ->update([
                'relevante_negocio' => true,
                'updated_at'        => now(),
            ]);

        $this->command->info("relevante_negocio = true en {$marcados} tipo(s) de documento legal.");

        if ($marcados === 0) {
            $this->command->warn(
                'Ninguno coincidió. Revisa que los nombres del array calcen con tu catálogo tipo_documento_legal.'
            );
        }
    }
}
