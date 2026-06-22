<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\OperadorViaje;
use App\Models\Sector;
use App\Models\TipoContribuyente;
use App\Models\TipoOperadorViaje;
use Illuminate\Database\Seeder;

class OperadoresViajeSeeder extends Seeder
{
    /**
     * FLUJO RECOMENDADO (datos SUNAT completos):
     *   1) Entra cada RUC por tu "buscar RUC" (dashboard) para que la empresa se
     *      cree con dirección fiscal, distrito_inei, fecha_inicio_actividades, etc.
     *   2) Corre este seeder: encontrará la empresa por RUC y SOLO enlazará el
     *      operador (no sobreescribe datos existentes).
     *
     * Si la empresa aún no existe, este seeder la crea con datos mínimos
     * (sin dirección/distrito/fecha — complétalos luego con buscar RUC).
     *
     * RUC verificados vía SUNAT (jun 2026), todos ACTIVO/HABIDO.
     * NO se siembra logo_path ni banner_path (por decisión del proyecto).
     */
    public function run(): void
    {
        $operadores = [
            // ── Aerolíneas ──────────────────────────────────────────────────────
            ['ruc' => '20341841357', 'razon_social' => 'LATAM AIRLINES PERU S.A.',            'nombre_comercial' => 'LATAM Airlines Perú',    'sigla' => 'LATAM',    'tipo' => 'Aerolínea'],
            ['ruc' => '20603446543', 'razon_social' => 'SKY AIRLINE PERU S.A.C.',             'nombre_comercial' => 'Sky Airline Perú',       'sigla' => 'SKY',      'tipo' => 'Aerolínea'],
            ['ruc' => '20607393649', 'razon_social' => 'JETSMART AIRLINES PERU S.A.C.',       'nombre_comercial' => 'JetSmart Airlines Perú', 'sigla' => 'JETSMART', 'tipo' => 'Aerolínea'],
            // ── Buses ───────────────────────────────────────────────────────────
            ['ruc' => '20100227461', 'razon_social' => 'TRANSPORTES CRUZ DEL SUR S.A.C.',     'nombre_comercial' => 'Cruz del Sur',           'sigla' => 'CDS',      'tipo' => 'Empresa de bus'],
            ['ruc' => '20135414931', 'razon_social' => 'EMPRESA DE TRANSPORTE TURISTICO OLANO S.A.', 'nombre_comercial' => 'Oltursa',          'sigla' => 'OLTURSA',  'tipo' => 'Empresa de bus'],
            ['ruc' => '20102427891', 'razon_social' => 'TURISMO CIVA S.A.C.',                 'nombre_comercial' => 'Civa',                   'sigla' => 'CIVA',     'tipo' => 'Empresa de bus'],
            // ── Trenes ──────────────────────────────────────────────────────────
            ['ruc' => '20431871808', 'razon_social' => 'PERURAIL S.A.',                       'nombre_comercial' => 'PeruRail',               'sigla' => 'PERURAIL', 'tipo' => 'Empresa de tren'],
            ['ruc' => '20515164945', 'razon_social' => 'INCA RAIL S.A.',                      'nombre_comercial' => 'Inca Rail',              'sigla' => 'INCARAIL', 'tipo' => 'Empresa de tren'],
        ];

        // Fallbacks SOLO para la rama de creación (cuando la empresa no existe aún).
        // Ajusta si quieres un sector específico; lo ideal es entrar por buscar RUC.
        $sectorFallback      = Sector::query()->value('id');
        $tipoContribFallback = TipoContribuyente::query()
            ->where('nombre', 'like', '%jur%')->value('id')
            ?? TipoContribuyente::query()->value('id');

        foreach ($operadores as $op) {
            $tipoOperador = TipoOperadorViaje::where('nombre', $op['tipo'])->first();

            if (! $tipoOperador) {
                $this->command?->warn("Tipo operador '{$op['tipo']}' no encontrado. Salto {$op['nombre_comercial']}.");
                continue;
            }

            // Empresa: si ya existe (la entraste por buscar RUC) NO se modifica.
            $empresa = Empresa::firstOrCreate(
                ['ruc' => $op['ruc']],
                [
                    'razon_social'          => $op['razon_social'],
                    'nombre_comercial'      => $op['nombre_comercial'],
                    'sigla'                 => $op['sigla'],
                    'estado_sunat'          => 'ACTIVO',
                    'condicion_sunat'       => 'HABIDO',
                    'sector_id'             => $sectorFallback,
                    'tipo_contribuyente_id' => $tipoContribFallback,
                    'activo'                => true,
                ]
            );

            // Operador: enlaza empresa + tipo. Idempotente por empresa_id.
            OperadorViaje::firstOrCreate(
                ['empresa_id' => $empresa->id],
                [
                    'tipo_operador_viaje_id' => $tipoOperador->id,
                    'sigla'                  => $op['sigla'],
                ]
            );

            $this->command?->info("OK: {$op['nombre_comercial']} ({$op['ruc']})");
        }
    }
}
