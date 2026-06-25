<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoNegocio extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'documentos_negocio';

    protected $fillable = [
        'negocio_id',
        'pedido_id',
        'pago_negocio_id',
        'tipo_documento_negocio_id',
        'nombre',
        'archivo_path',
        'fecha_emision',
        'fecha_vencimiento',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_emision'    => 'date',
            'fecha_vencimiento'=> 'date',
        ];
    }

    public function negocio(): BelongsTo
    {
        return $this->belongsTo(Negocio::class, 'negocio_id');
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function pagoNegocio(): BelongsTo
    {
        return $this->belongsTo(PagoNegocio::class, 'pago_negocio_id');
    }

    public function tipoDocumentoNegocio(): BelongsTo
    {
        return $this->belongsTo(TipoDocumentoNegocio::class, 'tipo_documento_negocio_id');
    }

    public function getArchivoUrlAttribute(): ?string
    {
        return $this->archivo_path ? asset('storage/' . $this->archivo_path) : null;
    }
}
