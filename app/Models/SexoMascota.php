<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SexoMascota extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'sexo_mascota';

    protected $fillable = [
        'nombre',
        'icono',
    ];

    public function mascotas(): HasMany
    {
        return $this->hasMany(Mascota::class, 'sexo_mascota_id');
    }
}
