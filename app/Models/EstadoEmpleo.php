<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoEmpleo extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'estado_empleo';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'color',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function empleos(): HasMany
    {
        return $this->hasMany(Empleo::class, 'estado_empleo_id');
    }
}
