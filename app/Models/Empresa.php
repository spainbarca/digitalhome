<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'empresas';

    protected $fillable = [
        'sector_id',
        'tipo_contribuyente_id',
        'ruc',
        'razon_social',
        'nombre_comercial',
        'estado_sunat',
        'condicion_sunat',
        'direccion_fiscal',
        'distrito_inei',
        'fecha_inicio_actividades',
        'telefono',
        'sitio_web',
        'email',
        'logo_url',
        'sunat_sincronizado_en',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio_actividades' => 'date',
            'sunat_sincronizado_en'    => 'datetime',
            'activo'                   => 'boolean',
        ];
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    public function tipoContribuyente(): BelongsTo
    {
        return $this->belongsTo(TipoContribuyente::class, 'tipo_contribuyente_id');
    }

    public function distrito(): BelongsTo
    {
        return $this->belongsTo(UbigeoDistrito::class, 'distrito_inei', 'inei');
    }

    public function proveedoresServicio(): HasMany
    {
        return $this->hasMany(ProveedorServicio::class, 'empresa_id');
    }
}
