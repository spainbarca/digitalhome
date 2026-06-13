<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoBeneficio extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tipo_beneficio';

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

    public function empleoBeneficios(): HasMany
    {
        return $this->hasMany(EmpleoBeneficio::class, 'tipo_beneficio_id');
    }
}
