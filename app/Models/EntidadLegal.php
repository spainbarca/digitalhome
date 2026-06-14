<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntidadLegal extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'entidades_legales';

    protected $fillable = [
        'empresa_id',
        'tipo_entidad_legal_id',
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

    public function tipoEntidadLegal(): BelongsTo
    {
        return $this->belongsTo(TipoEntidadLegal::class, 'tipo_entidad_legal_id');
    }

    public function distrito(): BelongsTo
    {
        return $this->belongsTo(UbigeoDistrito::class, 'distrito_inei', 'inei');
    }

    public function documentosLegales(): HasMany
    {
        return $this->hasMany(DocumentoLegal::class, 'entidad_legal_id');
    }

    public function getSiglaResueltaAttribute(): ?string
    {
        return $this->sigla ?: $this->empresa?->sigla;
    }
}
