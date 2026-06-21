<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperadorViaje extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'operadores_viaje';

    protected $fillable = [
        'empresa_id',
        'tipo_operador_viaje_id',
        'sigla',
        'logo_path',
        'banner_path',
        'codigo_iata',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function tipoOperadorViaje(): BelongsTo
    {
        return $this->belongsTo(TipoOperadorViaje::class, 'tipo_operador_viaje_id');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'operador_viaje_id');
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
