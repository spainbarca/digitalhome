<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoCompra extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'documentos_compra';

    protected $fillable = [
        'hogar_id',
        'compra_id',
        'tipo_documento_compra_id',
        'archivo_path',
        'titulo',
        'fecha_documento',
        'fecha_vencimiento',
    ];

    protected function casts(): array
    {
        return [
            'fecha_documento'    => 'date',
            'fecha_vencimiento'  => 'date',
        ];
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    public function tipoDocumentoCompra(): BelongsTo
    {
        return $this->belongsTo(TipoDocumentoCompra::class, 'tipo_documento_compra_id');
    }
}
