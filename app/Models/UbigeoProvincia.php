<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UbigeoProvincia extends Model
{
    use HasFactory;

    protected $table = 'ubigeo_provincias';

    protected $primaryKey = 'inei';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'inei',
        'reniec',
        'departamento_inei',
        'departamento',
        'provincia',
        'region',
        'macroregion_inei',
        'macroregion_minsa',
        'iso_3166_2',
        'fips',
        'capital',
        'superficie',
        'pob_densidad_2020',
        'altitude',
        'latitude',
        'longitude',
        'indice_densidad_estado',
        'indice_vulnerabilidad_alimentaria',
        'idh_2019',
        'pct_pobreza_total',
        'pct_pobreza_extrema',
    ];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(UbigeoDepartamento::class, 'departamento_inei', 'inei');
    }

    public function distritos(): HasMany
    {
        return $this->hasMany(UbigeoDistrito::class, 'provincia_inei', 'inei');
    }
}
