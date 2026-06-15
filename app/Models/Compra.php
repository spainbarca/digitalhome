<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compra extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'compras';

    protected $fillable = [
        'hogar_id',
        'miembro_id',
        'comercio_id',
        'producto_financiero_id',
        'categoria_compra_id',
        'metodo_pago_id',
        'moneda_id',
        'fecha_compra',
        'total',
        'concepto',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha_compra' => 'date',
            'total'        => 'decimal:2',
        ];
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function miembro(): BelongsTo
    {
        return $this->belongsTo(HogarMiembro::class, 'miembro_id');
    }

    public function comercio(): BelongsTo
    {
        return $this->belongsTo(Comercio::class, 'comercio_id');
    }

    public function categoriaCompra(): BelongsTo
    {
        return $this->belongsTo(CategoriaCompra::class, 'categoria_compra_id');
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function productoFinanciero(): BelongsTo
    {
        return $this->belongsTo(ProductoFinanciero::class, 'producto_financiero_id');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoCompra::class, 'compra_id');
    }
}
