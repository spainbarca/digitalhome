<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoDocumentoSeeder extends Seeder
{
    public function run(): void
    {
        // Fuente: SUNAT - Tabla 1: Tipo de Documento de Identidad (Anexo 112-2021)
        $documentos = [
            ['codigo' => 'DNI',     'nombre' => 'DNI - Documento Nacional de Identidad',    'descripcion' => 'Documento oficial de identidad para ciudadanos peruanos. Emitido por RENIEC',                   'longitud' => 8,    'es_numerico' => true],
            ['codigo' => 'CE',      'nombre' => 'CE - Carné de Extranjería',                'descripcion' => 'Documento de identidad para extranjeros residentes en Perú. Emitido por MIGRACIONES',           'longitud' => 9,    'es_numerico' => false],
            ['codigo' => 'RUC',     'nombre' => 'RUC - Registro Único de Contribuyentes',   'descripcion' => 'Número de contribuyente para personas naturales y jurídicas. Emitido por SUNAT',                 'longitud' => 11,   'es_numerico' => true],
            ['codigo' => 'PAS',     'nombre' => 'Pasaporte',                                'descripcion' => 'Documento de viaje internacional. Emitido por MIGRACIONES',                                      'longitud' => 9,    'es_numerico' => false],
            ['codigo' => 'CDI',     'nombre' => 'Cédula Diplomática de Identidad',          'descripcion' => 'Documento para personal diplomático acreditado en Perú. Emitido por RREE',                      'longitud' => null, 'es_numerico' => false],
            ['codigo' => 'IDPAIS',  'nombre' => 'Doc. de Identidad País de Residencia',     'descripcion' => 'Documento de identidad emitido por país de residencia para no domiciliados',                    'longitud' => null, 'es_numerico' => false],
            ['codigo' => 'TIN',     'nombre' => 'TIN - Tax Identification Number',          'descripcion' => 'Número de identificación tributaria para personas naturales extranjeras',                        'longitud' => null, 'es_numerico' => false],
            ['codigo' => 'IN',      'nombre' => 'IN - Identification Number',               'descripcion' => 'Número de identificación tributaria para personas jurídicas extranjeras',                        'longitud' => null, 'es_numerico' => false],
            ['codigo' => 'TAM',     'nombre' => 'TAM - Tarjeta Andina de Migración',        'descripcion' => 'Documento migratorio válido en países de la Comunidad Andina (Bolivia, Colombia, Ecuador, Perú)','longitud' => null, 'es_numerico' => false],
            ['codigo' => 'PTP',     'nombre' => 'PTP - Permiso Temporal de Permanencia',    'descripcion' => 'Permiso migratorio temporal. Principalmente para ciudadanos venezolanos',                       'longitud' => null, 'es_numerico' => false],
        ];

        foreach ($documentos as $doc) {
            DB::table('tipos_documento')->insertOrIgnore([
                'id'          => Str::uuid(),
                'codigo'      => $doc['codigo'],
                'nombre'      => $doc['nombre'],
                'descripcion' => $doc['descripcion'],
                'longitud'    => $doc['longitud'],
                'es_numerico' => $doc['es_numerico'],
                'estado'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
