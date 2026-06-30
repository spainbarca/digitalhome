<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Mascota extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'mascotas';

    protected $fillable = [
        'hogar_id',
        'especie_id',
        'raza_id',
        'sexo_mascota_id',
        'origen_mascota_id',
        'estado_mascota_id',
        'centro_veterinario_id',
        'nombre',
        'fecha_nacimiento',
        'fecha_nacimiento_aproximada',
        'color',
        'senas',
        'microchip',
        'esterilizado',
        'fecha_esterilizacion',
        'foto_path',
        'fecha_fallecimiento',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento'            => 'date',
            'fecha_nacimiento_aproximada' => 'boolean',
            'esterilizado'                => 'boolean',
            'fecha_esterilizacion'        => 'date',
            'fecha_fallecimiento'         => 'date',
        ];
    }

    // ── Relaciones ──────────────────────────────────────────────────────────

    public function hogar(): BelongsTo
    {
        return $this->belongsTo(Hogar::class, 'hogar_id');
    }

    public function especie(): BelongsTo
    {
        return $this->belongsTo(Especie::class, 'especie_id');
    }

    public function raza(): BelongsTo
    {
        return $this->belongsTo(Raza::class, 'raza_id');
    }

    public function sexoMascota(): BelongsTo
    {
        return $this->belongsTo(SexoMascota::class, 'sexo_mascota_id');
    }

    public function origenMascota(): BelongsTo
    {
        return $this->belongsTo(OrigenMascota::class, 'origen_mascota_id');
    }

    public function estadoMascota(): BelongsTo
    {
        return $this->belongsTo(EstadoMascota::class, 'estado_mascota_id');
    }

    public function centroVeterinario(): BelongsTo
    {
        return $this->belongsTo(CentroVeterinario::class, 'centro_veterinario_id');
    }

    public function atenciones(): HasMany
    {
        return $this->hasMany(AtencionVeterinaria::class, 'mascota_id');
    }

    public function eventosSanitarios(): HasMany
    {
        return $this->hasMany(EventoSanitario::class, 'mascota_id');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoMascota::class, 'mascota_id');
    }

    // ── Accesores ───────────────────────────────────────────────────────────

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto_path ? Storage::url($this->foto_path) : null;
    }

    public function getEdadAttribute(): ?string
    {
        if (! $this->fecha_nacimiento) {
            return null;
        }

        $años = $this->fecha_nacimiento->diffInYears(now());

        if ($años >= 1) {
            $texto = $años === 1 ? '1 año' : "{$años} años";
        } else {
            $meses = $this->fecha_nacimiento->diffInMonths(now());
            $texto = $meses <= 1 ? '1 mes' : "{$meses} meses";
        }

        return $this->fecha_nacimiento_aproximada ? "~{$texto}" : $texto;
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    public function scopeDelHogar(Builder $query, string $hogarId): Builder
    {
        return $query->where('hogar_id', $hogarId);
    }
}
