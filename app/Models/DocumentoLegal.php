<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoLegal extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'documentos_legales';

    protected $fillable = [
        'hogar_id',
        'hogar_miembro_id',
        'propiedad_inmueble_id',
        'tipo_documento_legal_id',
        'estado_documento_legal_id',
        'entidad_legal_id',
        'titulo',
        'numero_documento',
        'fecha_emision',
        'fecha_vencimiento',
        'file_path',
        'notas',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'fecha_emision'     => 'date',
            'fecha_vencimiento' => 'date',
            'activo'            => 'boolean',
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

    public function propiedadInmueble(): BelongsTo
    {
        return $this->belongsTo(PropiedadInmueble::class, 'propiedad_inmueble_id');
    }

    public function tipoDocumentoLegal(): BelongsTo
    {
        return $this->belongsTo(TipoDocumentoLegal::class, 'tipo_documento_legal_id');
    }

    public function estadoDocumentoLegal(): BelongsTo
    {
        return $this->belongsTo(EstadoDocumentoLegal::class, 'estado_documento_legal_id');
    }

    public function entidadLegal(): BelongsTo
    {
        return $this->belongsTo(EntidadLegal::class, 'entidad_legal_id');
    }
}
