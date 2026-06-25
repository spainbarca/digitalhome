<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'pedidos';

    protected $fillable = [
        'negocio_id',
        'proveedor_negocio_id',
        'numero',
        'descripcion',
        'fecha',
        'moneda_id',
        'total',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'total' => 'decimal:2',
        ];
    }

    public function negocio(): BelongsTo
    {
        return $this->belongsTo(Negocio::class, 'negocio_id');
    }

    public function proveedorNegocio(): BelongsTo
    {
        return $this->belongsTo(ProveedorNegocio::class, 'proveedor_negocio_id');
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function documentosNegocio(): HasMany
    {
        return $this->hasMany(DocumentoNegocio::class, 'pedido_id');
    }
}
