<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoDocumentoLegalSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            // Personal
            ['nombre' => 'DNI',                          'descripcion' => 'Documento Nacional de Identidad',                              'icono' => 'badge',                      'categoria' => 'personal',   'requiere_vencimiento' => true],
            ['nombre' => 'Pasaporte',                    'descripcion' => 'Pasaporte de viaje internacional',                             'icono' => 'travel_explore',             'categoria' => 'personal',   'requiere_vencimiento' => true],
            ['nombre' => 'Partida de nacimiento',        'descripcion' => 'Acta de nacimiento emitida por RENIEC',                        'icono' => 'child_care',                 'categoria' => 'personal',   'requiere_vencimiento' => false],
            ['nombre' => 'Partida de matrimonio',        'descripcion' => 'Acta de matrimonio civil',                                     'icono' => 'favorite',                   'categoria' => 'personal',   'requiere_vencimiento' => false],
            ['nombre' => 'Partida de defunción',         'descripcion' => 'Acta de defunción',                                            'icono' => 'sentiment_very_dissatisfied','categoria' => 'personal',   'requiere_vencimiento' => false],
            ['nombre' => 'Poder notarial',               'descripcion' => 'Poder otorgado ante notario público',                          'icono' => 'gavel',                      'categoria' => 'personal',   'requiere_vencimiento' => false],
            ['nombre' => 'Testamento',                   'descripcion' => 'Documento de última voluntad',                                 'icono' => 'history_edu',                'categoria' => 'personal',   'requiere_vencimiento' => false],
            // Propiedad
            ['nombre' => 'Escritura pública',            'descripcion' => 'Escritura de compraventa o transferencia',                     'icono' => 'home',                       'categoria' => 'propiedad',  'requiere_vencimiento' => false],
            ['nombre' => 'Título de propiedad',          'descripcion' => 'Título registrado ante SUNARP',                                'icono' => 'domain',                     'categoria' => 'propiedad',  'requiere_vencimiento' => false],
            ['nombre' => 'Ficha SUNARP',                 'descripcion' => 'Ficha registral del inmueble',                                 'icono' => 'article',                    'categoria' => 'propiedad',  'requiere_vencimiento' => false],
            ['nombre' => 'HR/PU',                        'descripcion' => 'Hoja de resumen y predio urbano municipal',                    'icono' => 'receipt_long',               'categoria' => 'propiedad',  'requiere_vencimiento' => false],
            ['nombre' => 'Plano arquitectónico',         'descripcion' => 'Plano de distribución del inmueble',                           'icono' => 'architecture',               'categoria' => 'propiedad',  'requiere_vencimiento' => false],
            ['nombre' => 'Plano estructural',            'descripcion' => 'Plano de estructura del inmueble',                             'icono' => 'foundation',                 'categoria' => 'propiedad',  'requiere_vencimiento' => false],
            ['nombre' => 'Arbitrios municipales',        'descripcion' => 'Pago de arbitrios de limpieza, parques y serenazgo',           'icono' => 'account_balance',            'categoria' => 'propiedad',  'requiere_vencimiento' => true],
            ['nombre' => 'Reglamento interno',           'descripcion' => 'Reglamento interno del edificio o condominio',                 'icono' => 'menu_book',                  'categoria' => 'propiedad',  'requiere_vencimiento' => false],
            ['nombre' => 'Acta de junta de propietarios','descripcion' => 'Acta de reunión de junta de propietarios',                    'icono' => 'groups',                     'categoria' => 'propiedad',  'requiere_vencimiento' => false],
            // Seguro
            ['nombre' => 'Póliza de seguro de inmueble','descripcion' => 'Seguro contra siniestros del inmueble',                        'icono' => 'security',                   'categoria' => 'seguro',     'requiere_vencimiento' => true],
            ['nombre' => 'Póliza de seguro de vida',    'descripcion' => 'Seguro de vida personal',                                      'icono' => 'favorite_border',            'categoria' => 'seguro',     'requiere_vencimiento' => true],
            ['nombre' => 'Póliza de seguro médico',     'descripcion' => 'Seguro médico o EPS',                                          'icono' => 'health_and_safety',          'categoria' => 'seguro',     'requiere_vencimiento' => true],
            // Contrato
            ['nombre' => 'Contrato de arrendamiento',   'descripcion' => 'Contrato de alquiler como propietario o inquilino',            'icono' => 'handshake',                  'categoria' => 'contrato',   'requiere_vencimiento' => true],
            ['nombre' => 'Contrato de servicios',       'descripcion' => 'Contrato con proveedor de servicios',                          'icono' => 'description',                'categoria' => 'contrato',   'requiere_vencimiento' => true],
            // Denuncia
            ['nombre' => 'Denuncia policial',           'descripcion' => 'Denuncia registrada ante la PNP',                              'icono' => 'local_police',               'categoria' => 'denuncia',   'requiere_vencimiento' => false],
            ['nombre' => 'Demanda judicial',            'descripcion' => 'Documento de proceso judicial',                                'icono' => 'balance',                    'categoria' => 'denuncia',   'requiere_vencimiento' => false],
            ['nombre' => 'Conciliación extrajudicial',  'descripcion' => 'Acta de conciliación fuera del proceso judicial',              'icono' => 'handshake',                  'categoria' => 'denuncia',   'requiere_vencimiento' => false],
            // Otro
            ['nombre' => 'Otro',                        'descripcion' => 'Documento legal no categorizado',                              'icono' => 'folder',                     'categoria' => 'otro',       'requiere_vencimiento' => false],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_documento_legal')->insertOrIgnore([
                'id'                   => Str::uuid(),
                'nombre'               => $tipo['nombre'],
                'descripcion'          => $tipo['descripcion'],
                'icono'                => $tipo['icono'],
                'categoria'            => $tipo['categoria'],
                'requiere_vencimiento' => $tipo['requiere_vencimiento'],
                'activo'               => true,
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);
        }
    }
}
