<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntidadFinanciera extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'entidades_financieras';

    protected $fillable = [
        'empresa_id',
        'tipo_entidad_financiera_id',
        'sigla',
        'logo_url',
        'codigo_sbs',
        'swift',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function tipoEntidadFinanciera(): BelongsTo
    {
        return $this->belongsTo(TipoEntidadFinanciera::class, 'tipo_entidad_financiera_id');
    }

    public function productosFinancieros(): HasMany
    {
        return $this->hasMany(ProductoFinanciero::class, 'entidad_financiera_id');
    }

    public function getSiglaResueltaAttribute(): ?string
    {
        return $this->sigla ?: $this->empresa?->sigla;
    }

    public function getLogoResueltoAttribute(): ?string
    {
        return $this->logo_url ?: $this->empresa?->logo_url;
    }
}
