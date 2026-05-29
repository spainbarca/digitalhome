<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropiedadInmueble extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'propiedades_inmueble';

    protected $fillable = [
        'persona_id',
        'tipo_inmueble_id',
        'alias',
        'direccion',
        'referencia',
        'avatar_url',
        'distrito_inei',
        'pais_iso2',
        'ciudad_id',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Persona::class, 'persona_id');
    }

    public function tipoInmueble(): BelongsTo
    {
        return $this->belongsTo(TipoInmueble::class, 'tipo_inmueble_id');
    }

    public function distrito(): BelongsTo
    {
        return $this->belongsTo(UbigeoDistrito::class, 'distrito_inei', 'inei');
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class, 'pais_iso2', 'iso2');
    }

    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }

    public function cuentasServicio(): HasMany
    {
        return $this->hasMany(CuentaServicio::class, 'propiedad_id');
    }
}
