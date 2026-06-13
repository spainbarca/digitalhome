<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoLaboral extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'documentos_laborales';

    protected $fillable = [
        'empleo_id',
        'tipo_documento_laboral_id',
        'titulo',
        'numero_documento',
        'periodo_mes',
        'periodo_anio',
        'fecha_emision',
        'fecha_vencimiento',
        'file_path',
        'notas',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'periodo_mes'       => 'integer',
            'periodo_anio'      => 'integer',
            'fecha_emision'     => 'date',
            'fecha_vencimiento' => 'date',
            'activo'            => 'boolean',
        ];
    }

    public function empleo(): BelongsTo
    {
        return $this->belongsTo(Empleo::class, 'empleo_id');
    }

    public function tipoDocumentoLaboral(): BelongsTo
    {
        return $this->belongsTo(TipoDocumentoLaboral::class, 'tipo_documento_laboral_id');
    }
}
