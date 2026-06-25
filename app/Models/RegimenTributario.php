<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegimenTributario extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'regimen_tributario';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'icono',
        'activo',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function negocios(): HasMany
    {
        return $this->hasMany(Negocio::class, 'regimen_tributario_id');
    }
}
