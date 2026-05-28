<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetodoPago extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'metodo_pago';

    protected $fillable = [
        'nombre',
        'icono',
        'logo',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function documentosServicio(): HasMany
    {
        return $this->hasMany(DocumentoServicio::class, 'metodo_pago_id');
    }
}
