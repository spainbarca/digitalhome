<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaccion extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'transacciones';

    protected $fillable = [
        'producto_financiero_id',
        'producto_destino_id',
        'tipo_transaccion_id',
        'metodo_pago_id',
        'moneda_id',
        'monto',
        'fecha',
        'glosa',
        'nro_operacion',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'fecha' => 'datetime',
        ];
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(ProductoFinanciero::class, 'producto_financiero_id');
    }

    public function productoDestino(): BelongsTo
    {
        return $this->belongsTo(ProductoFinanciero::class, 'producto_destino_id');
    }

    public function tipoTransaccion(): BelongsTo
    {
        return $this->belongsTo(TipoTransaccion::class, 'tipo_transaccion_id');
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function documentosFinancieros(): HasMany
    {
        return $this->hasMany(DocumentoFinanciero::class, 'transaccion_id');
    }
}
