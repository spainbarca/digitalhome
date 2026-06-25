<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProveedorNegocio extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'proveedores_negocio';

    protected $fillable = [
        'hogar_id',
        'empresa_id',
        'nombre',
        'sigla',
        'condicion_pago',
        'contacto',
        'telefono',
        'logo_path',
        'banner_path',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'proveedor_negocio_id');
    }

    public function getSiglaResueltaAttribute(): ?string
    {
        return $this->sigla ?: $this->empresa?->sigla;
    }

    public function getNombreComercialResueltoAttribute(): ?string
    {
        return $this->empresa?->nombre_comercial ?: $this->empresa?->razon_social;
    }

    public function getLogoResueltoAttribute(): ?string
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }
        if ($this->empresa?->logo_url) {
            return asset('storage/' . $this->empresa->logo_url);
        }
        return null;
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner_path ? asset('storage/' . $this->banner_path) : null;
    }
}
