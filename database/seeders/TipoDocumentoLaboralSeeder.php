<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoDocumentoLaboralSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $tipos = [
            // [nombre, descripcion, icono, requiere_vencimiento]
            ['Contrato de trabajo', 'Contrato laboral firmado con el empleador', 'description', true],
            ['Contrato a plazo fijo', 'Contrato sujeto a modalidad con fecha de término', 'event_busy', true],
            ['Contrato indefinido', 'Contrato a plazo indeterminado', 'all_inclusive', false],
            ['Adenda de contrato', 'Modificación o ampliación de un contrato existente', 'edit_note', false],
            ['Boleta de pago', 'Boleta de remuneración mensual', 'receipt_long', false],
            ['Liquidación de beneficios sociales', 'Liquidación al cese del vínculo laboral', 'request_quote', false],
            ['Certificado de trabajo', 'Certificado que acredita la experiencia laboral', 'workspace_premium', false],
            ['Constancia de trabajo', 'Constancia de labores emitida por el empleador', 'verified', false],
            ['Carta de renuncia', 'Documento de renuncia voluntaria', 'logout', false],
            ['Carta de despido', 'Carta de cese o término por parte del empleador', 'gavel', false],
            ['Carta de preaviso', 'Comunicación previa al cese', 'notification_important', false],
            ['Convenio de prácticas', 'Convenio de prácticas pre o profesionales', 'school', true],
            ['Acuerdo de confidencialidad', 'Acuerdo de no divulgación (NDA)', 'lock', false],
            ['Memorándum', 'Comunicación interna o llamada de atención', 'sticky_note_2', false],
            ['Evaluación de desempeño', 'Reporte de evaluación de desempeño', 'assessment', false],
            ['Constancia de CTS', 'Constancia de depósito de CTS', 'savings', false],
            ['Certificado de retención de renta', 'Certificado de retenciones de quinta categoría', 'account_balance', false],
            ['Constancia de afiliación AFP/ONP', 'Constancia de afiliación al sistema pensionario', 'elderly', false],
            ['Otro', 'Documento laboral no categorizado', 'folder', false],
        ];

        foreach ($tipos as $t) {
            DB::table('tipo_documento_laboral')->insert([
                'id'                   => Str::uuid(),
                'nombre'               => $t[0],
                'descripcion'          => $t[1],
                'icono'                => $t[2],
                'requiere_vencimiento' => $t[3],
                'activo'               => true,
                'created_at'           => $now,
                'updated_at'           => $now,
            ]);
        }
    }
}
