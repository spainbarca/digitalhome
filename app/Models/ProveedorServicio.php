<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProveedorServicio extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'proveedores_servicio';

    protected $fillable = [
        'empresa_id',
        'tipo_servicio_id',
        'nombre_comercial',
        'sigla',
        'telefono',
        'sitio_web',
        'logo_url',
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

    public function tipoServicio(): BelongsTo
    {
        return $this->belongsTo(TipoServicio::class, 'tipo_servicio_id');
    }

    public function cuentasServicio(): HasMany
    {
        return $this->hasMany(CuentaServicio::class, 'proveedor_id');
    }

    public function getSiglaResueltaAttribute(): ?string
    {
        return $this->sigla ?: $this->empresa?->sigla;
    }
}
