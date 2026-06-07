<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\UbigeoDistrito;

class Comercio extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'comercios';

    protected $fillable = [
        'hogar_id',
        'empresa_id',
        'tipo_comercio_id',
        'nombre_referencial',
        'logo_path',
        'imagen_path',
        'banner_path',
        'es_online',
        'direccion',
        'pais_iso2',
        'ciudad_id',
        'distrito_inei',
        'notas',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'es_online' => 'boolean',
            'activo'    => 'boolean',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function tipoComercio(): BelongsTo
    {
        return $this->belongsTo(TipoComercio::class, 'tipo_comercio_id');
    }

    public function distrito(): BelongsTo
    {
        return $this->belongsTo(UbigeoDistrito::class, 'distrito_inei', 'inei');
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class, 'comercio_id');
    }

    public function getLogoEfectivoAttribute(): ?string
    {
        return $this->logo_path
            ? asset('storage/' . $this->logo_path)
            : $this->empresa?->logo_url;
    }

    public function getDistritoEfectivoAttribute(): ?UbigeoDistrito
    {
        return $this->distrito ?: $this->empresa?->distrito;
    }

    public function getDireccionEfectivaAttribute(): ?string
    {
        return $this->direccion ?: $this->empresa?->direccion_fiscal;
    }
}
