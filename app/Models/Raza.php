<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Raza extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'razas';

    protected $fillable = [
        'especie_id',
        'nombre',
        'icono',
    ];

    public function especie(): BelongsTo
    {
        return $this->belongsTo(Especie::class, 'especie_id');
    }

    public function mascotas(): HasMany
    {
        return $this->hasMany(Mascota::class, 'raza_id');
    }
}
