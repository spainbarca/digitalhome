<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pais extends Model
{
    use HasFactory;

    protected $table = 'paises';

    protected $primaryKey = 'id_pais';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_pais',
        'iso2',
        'nombre',
        'nombre_oficial',
        'iso3',
        'codigo_num',
        'miembro_onu',
        'codigo_tel',
        'dominio',
    ];

    protected function casts(): array
    {
        return [
            'miembro_onu' => 'boolean',
        ];
    }

    public function ciudades(): HasMany
    {
        return $this->hasMany(Ciudad::class, 'pais_iso2', 'iso2');
    }

    public function propiedadesInmueble(): HasMany
    {
        return $this->hasMany(PropiedadInmueble::class, 'pais_iso2', 'iso2');
    }
}
