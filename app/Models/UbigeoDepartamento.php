<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UbigeoDepartamento extends Model
{
    use HasFactory;

    protected $table = 'ubigeo_departamentos';

    protected $primaryKey = 'inei';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'inei',
        'reniec',
        'departamento',
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

    public function provincias(): HasMany
    {
        return $this->hasMany(UbigeoProvincia::class, 'departamento_inei', 'inei');
    }

    public function distritos(): HasMany
    {
        return $this->hasMany(UbigeoDistrito::class, 'departamento_inei', 'inei');
    }
}
