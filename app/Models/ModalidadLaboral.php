<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModalidadLaboral extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'modalidad_laboral';

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

    public function empleos(): HasMany
    {
        return $this->hasMany(Empleo::class, 'modalidad_laboral_id');
    }
}
