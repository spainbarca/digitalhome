<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovimientoPrestamo extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'movimientos_prestamo';

    protected $fillable = [
        'hogar_id',
        'prestatario_id',
        'concepto_pago_id',
        'tipo',
        'monto',
        'glosa',
        'fecha',
        'metodo_pago_id',
        'comprobante_path',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'fecha' => 'date',
        ];
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function prestatario(): BelongsTo
    {
        return $this->belongsTo(Prestatario::class, 'prestatario_id');
    }

    public function conceptoPago(): BelongsTo
    {
        return $this->belongsTo(ConceptoPago::class, 'concepto_pago_id');
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

    public function getSignoMontoAttribute(): float
    {
        return match ($this->tipo) {
            'cargo'     => (float) $this->monto,
            'abono',
            'descuento' => -(float) $this->monto,
            default     => (float) $this->monto,
        };
    }

    public function getColorTipoAttribute(): string
    {
        return match ($this->tipo) {
            'cargo'     => '#dc2626',
            'abono'     => '#22c55e',
            'descuento' => '#f97316',
            default     => '#6b7280',
        };
    }
}
