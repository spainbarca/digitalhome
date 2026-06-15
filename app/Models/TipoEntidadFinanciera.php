<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoEntidadFinanciera extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tipo_entidad_financiera';

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

    public function entidadesFinancieras(): HasMany
    {
        return $this->hasMany(EntidadFinanciera::class, 'tipo_entidad_financiera_id');
    }
}
