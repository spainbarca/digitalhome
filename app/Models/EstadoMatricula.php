<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstadoMatricula extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'estados_matricula';

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

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'estado_matricula_id');
    }
}
