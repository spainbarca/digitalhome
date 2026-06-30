<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CentroVeterinario extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'centros_veterinarios';

    protected $fillable = [
        'hogar_id',
        'empresa_id',
        'tipo_centro_medico_id',
        'nombre_referencial',
        'sigla',
        'imagen_path',
        'notas',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function tipoCentroMedico(): BelongsTo
    {
        return $this->belongsTo(TipoCentroMedico::class, 'tipo_centro_medico_id');
    }

    public function getSiglaResueltaAttribute(): ?string
    {
        return $this->sigla ?: $this->empresa?->sigla;
    }

    // Relaciones dominio mascotas
    public function mascotas(): HasMany
    {
        return $this->hasMany(Mascota::class, 'centro_veterinario_id');
    }

    public function atenciones(): HasMany
    {
        return $this->hasMany(AtencionVeterinaria::class, 'centro_veterinario_id');
    }

    public function eventosSanitarios(): HasMany
    {
        return $this->hasMany(EventoSanitario::class, 'centro_veterinario_id');
    }
}
