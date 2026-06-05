<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class NivelEducativo extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'niveles_educativos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'orden',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'orden'  => 'integer',
            'activo' => 'boolean',
        ];
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'nivel_educativo_id');
    }
}
