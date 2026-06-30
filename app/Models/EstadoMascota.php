<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstadoMascota extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'estado_mascota';

    protected $fillable = [
        'nombre',
        'icono',
        'color',
    ];

    public function mascotas(): HasMany
    {
        return $this->hasMany(Mascota::class, 'estado_mascota_id');
    }
}
