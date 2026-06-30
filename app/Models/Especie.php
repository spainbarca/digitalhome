<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Especie extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'especies';

    protected $fillable = [
        'nombre',
        'icono',
    ];

    public function razas(): HasMany
    {
        return $this->hasMany(Raza::class, 'especie_id');
    }

    public function mascotas(): HasMany
    {
        return $this->hasMany(Mascota::class, 'especie_id');
    }
}
