<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'categorias';

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

    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class, 'categoria_id');
    }
}
