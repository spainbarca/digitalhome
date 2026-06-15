<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoFinanciero extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'documentos_financieros';

    protected $fillable = [
        'hogar_id',
        'producto_financiero_id',
        'transaccion_id',
        'tipo_documento_financiero_id',
        'titulo',
        'periodo',
        'fecha_emision',
        'fecha_vencimiento',
        'archivo_path',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha_emision'    => 'date',
            'fecha_vencimiento'=> 'date',
        ];
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(ProductoFinanciero::class, 'producto_financiero_id');
    }

    public function transaccion(): BelongsTo
    {
        return $this->belongsTo(Transaccion::class, 'transaccion_id');
    }

    public function tipoDocumentoFinanciero(): BelongsTo
    {
        return $this->belongsTo(TipoDocumentoFinanciero::class, 'tipo_documento_financiero_id');
    }
}
