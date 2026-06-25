<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PagoNegocio extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'pagos_negocio';

    protected $fillable = [
        'negocio_id',
        'entidad_receptora_id',
        'tipo_pago_negocio_id',
        'monto',
        'moneda_id',
        'fecha_pago',
        'periodo',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'monto'      => 'decimal:2',
            'fecha_pago' => 'date',
        ];
    }

    public function negocio(): BelongsTo
    {
        return $this->belongsTo(Negocio::class, 'negocio_id');
    }

    public function entidadReceptora(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'entidad_receptora_id');
    }

    public function tipoPagoNegocio(): BelongsTo
    {
        return $this->belongsTo(TipoPagoNegocio::class, 'tipo_pago_negocio_id');
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function documentosNegocio(): HasMany
    {
        return $this->hasMany(DocumentoNegocio::class, 'pago_negocio_id');
    }
}
