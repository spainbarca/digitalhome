<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoEventoSanitario extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tipo_evento_sanitario';

    protected $fillable = [
        'nombre',
        'icono',
        'color',
    ];

    public function eventosSanitarios(): HasMany
    {
        return $this->hasMany(EventoSanitario::class, 'tipo_evento_sanitario_id');
    }
}
