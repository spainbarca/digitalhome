<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parentesco extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'parentescos';

    protected $fillable = [
        'nombre',
        'nombre_inverso',
        'grupo',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function hogarMiembros(): HasMany
    {
        return $this->hasMany(HogarMiembro::class, 'parentesco_id');
    }
}
