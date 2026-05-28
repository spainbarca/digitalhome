<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UbigeoDistrito extends Model
{
    use HasFactory;

    protected $table = 'ubigeo_distritos';

    protected $primaryKey = 'inei';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'inei',
        'reniec',
        'departamento_inei',
        'provincia_inei',
        'departamento',
        'provincia',
        'distrito',
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
        'indice_vulnerabilidad_alimentaria',
        'idh_2019',
        'pct_pobreza_total',
        'pct_pobreza_extrema',
    ];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(UbigeoDepartamento::class, 'departamento_inei', 'inei');
    }

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(UbigeoProvincia::class, 'provincia_inei', 'inei');
    }

    public function propiedadesInmueble(): HasMany
    {
        return $this->hasMany(PropiedadInmueble::class, 'distrito_inei', 'inei');
    }

    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class, 'distrito_inei', 'inei');
    }
}
