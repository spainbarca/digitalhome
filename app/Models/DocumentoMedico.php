<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoMedico extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'documentos_medicos';

    protected $fillable = [
        'hogar_id',
        'hogar_miembro_id',
        'consulta_medica_id',
        'tipo_documento_medico_id',
        'nombre',
        'descripcion',
        'archivo_url',
        'fecha_documento',
    ];

    protected function casts(): array
    {
        return [
            'fecha_documento' => 'date',
        ];
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function hogarMiembro(): BelongsTo
    {
        return $this->belongsTo(HogarMiembro::class, 'hogar_miembro_id');
    }

    public function consultaMedica(): BelongsTo
    {
        return $this->belongsTo(ConsultaMedica::class, 'consulta_medica_id');
    }

    public function tipoDocumentoMedico(): BelongsTo
    {
        return $this->belongsTo(TipoDocumentoMedico::class, 'tipo_documento_medico_id');
    }
}
