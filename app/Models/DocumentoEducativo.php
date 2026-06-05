<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoEducativo extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'documentos_educativos';

    protected $fillable = [
        'hogar_id',
        'hogar_miembro_id',
        'matricula_id',
        'tipo_documento_educativo_id',
        'titulo',
        'fecha_documento',
        'archivo_path',
        'archivo_nombre_original',
        'archivo_mime',
        'archivo_size',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha_documento' => 'date',
            'archivo_size'    => 'integer',
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

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class, 'matricula_id');
    }

    public function tipoDocumentoEducativo(): BelongsTo
    {
        return $this->belongsTo(TipoDocumentoEducativo::class, 'tipo_documento_educativo_id');
    }
}
