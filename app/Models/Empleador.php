<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Empleador extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'empleadores';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'sigla',
        'descripcion',
        'logo_path',
        'imagen_path',
        'banner_path',
        'telefono',
        'email',
        'sitio_web',
        'direccion',
        'distrito_inei',
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

    public function distrito(): BelongsTo
    {
        return $this->belongsTo(UbigeoDistrito::class, 'distrito_inei', 'inei');
    }

    public function empleos(): HasMany
    {
        return $this->hasMany(Empleo::class, 'empleador_id');
    }

    public function getLogoDisplayUrlAttribute(): ?string
    {
        if ($this->logo_path) {
            return Storage::url($this->logo_path);
        }
        if ($this->empresa?->logo_url) {
            return Storage::url($this->empresa->logo_url);
        }
        return null;
    }

    public function getBannerDisplayUrlAttribute(): ?string
    {
        if ($this->banner_path) {
            return Storage::url($this->banner_path);
        }
        return null;
    }

    public function getDireccionEfectivaAttribute(): ?string
    {
        return $this->direccion ?? $this->empresa?->direccion_fiscal;
    }

    public function getDistritoEfectivoAttribute(): ?UbigeoDistrito
    {
        return $this->distrito ?? $this->empresa?->distrito;
    }

    public function getSiglaResueltaAttribute(): ?string
    {
        return $this->sigla ?: $this->empresa?->sigla;
    }
}
