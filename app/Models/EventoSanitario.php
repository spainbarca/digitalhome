<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventoSanitario extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'eventos_sanitarios';

    protected $fillable = [
        'mascota_id',
        'tipo_evento_sanitario_id',
        'centro_veterinario_id',
        'fecha_aplicacion',
        'producto',
        'lote',
        'proxima_dosis',
        'costo',
        'moneda_id',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha_aplicacion' => 'date',
            'proxima_dosis'    => 'date',
            'costo'            => 'decimal:2',
        ];
    }

    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }

    public function tipoEventoSanitario(): BelongsTo
    {
        return $this->belongsTo(TipoEventoSanitario::class, 'tipo_evento_sanitario_id');
    }

    public function centroVeterinario(): BelongsTo
    {
        return $this->belongsTo(CentroVeterinario::class, 'centro_veterinario_id');
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoMascota::class, 'evento_sanitario_id');
    }

    // ── Scopes (hook recordatorios transversal) ──────────────────────────────

    public function scopeConProximaDosis(Builder $query): Builder
    {
        return $query->whereNotNull('proxima_dosis');
    }

    public function scopeProximaDosisHasta(Builder $query, mixed $fecha): Builder
    {
        return $query->whereNotNull('proxima_dosis')->where('proxima_dosis', '<=', $fecha);
    }
}
