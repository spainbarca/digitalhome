<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoInstitucionEducativa extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tipo_institucion_educativa';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function institucionesEducativas(): HasMany
    {
        return $this->hasMany(InstitucionEducativa::class, 'tipo_institucion_educativa_id');
    }
}
