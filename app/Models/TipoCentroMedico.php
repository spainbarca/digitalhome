<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoCentroMedico extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tipos_centro_medico';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'imagen_path',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function centrosMedicos(): HasMany
    {
        return $this->hasMany(CentroMedico::class, 'tipo_centro_medico_id');
    }
}
