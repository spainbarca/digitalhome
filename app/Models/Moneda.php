<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Moneda extends Model
{
    use HasFactory;

    protected $table = 'monedas';

    protected $fillable = [
        'nombre',
        'codigo',
        'simbolo',
        'tasa_cambio',
        'moneda_local',
    ];

    protected function casts(): array
    {
        return [
            'tasa_cambio'   => 'decimal:6',
            'moneda_local'  => 'boolean',
        ];
    }

    public function documentosServicio(): HasMany
    {
        return $this->hasMany(DocumentoServicio::class, 'moneda_id');
    }
}
